<?php
namespace Cobweb\Sqlprofiler\Xclass;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Francois Suter <typo3@cobweb.ch>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Overrides the standard database connection class to log queries.
 *
 * @author Francois Suter <typo3@cobweb.ch>
 * @package TYPO3
 */
class DatabaseConnection extends \TYPO3\CMS\Core\Database\DatabaseConnection {
	protected $profilerConfiguration = array();

	public function __construct() {
		$GLOBALS['T3_VAR']['tx_sqlprofiler'] = array(
			'queries' => array(),
			'queryDetails' => array()
		);
	}

	public function __destruct() {
		$this->writeProfilingInformation();
	}

	/**
	 * Overrides the central query method to log queries.
	 *
	 * @param string $query The query to send to the database
	 * @return bool|\mysqli_result
	 */
	protected function query($query) {
		if (!$this->isConnected) {
			$this->connectDB();
		}
		// Check if query should be profiled. If yes, gather profiling information, if not, execute it right away.
		if ($this->shouldProfile($query)) {
			$queryHash = md5($query);
			if (!isset($GLOBALS['T3_VAR']['tx_sqlprofiler']['queries'][$queryHash])) {
				$GLOBALS['T3_VAR']['tx_sqlprofiler']['queries'][$queryHash] = $query;
				$GLOBALS['T3_VAR']['tx_sqlprofiler']['queryDetails'][$queryHash] = array();
			}
			// Collect the page id during the run, because it is not available when either construct() or destruct() are called
			$GLOBALS['T3_VAR']['tx_sqlprofiler']['pageId'] = (isset($GLOBALS['TSFE']) && !empty($GLOBALS['TSFE']->id)) ? $GLOBALS['TSFE']->id : 0;
			$startTime = microtime(TRUE);
			$result = $this->link->query($query);
			$endTime = microtime(TRUE);
			$GLOBALS['T3_VAR']['tx_sqlprofiler']['queryDetails'][$queryHash][] = array(
				'execution_time' => ($endTime - $startTime) * 1000000,
				'debug_trail' => \TYPO3\CMS\Core\Utility\DebugUtility::debugTrail()
			);
		} else {
			$result = $this->link->query($query);
		}
		return $result;
	}

	/**
	 * Checks whether a given query should be profiled or not.
	 *
	 * @param string $query SQL statement
	 * @return bool
	 */
	protected function shouldProfile($query) {
		// Exit early if no profiling should be done at all
		if ($GLOBALS['T3_VAR']['tx_sqlprofiler']['skipProfiling']) {
			return TRUE;
		}

		// Don't log own queries
		if (strpos($query, 'tx_sqlprofile') === FALSE) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Writes the collected profiling information to the database.
	 *
	 * @return void
	 */
	protected function writeProfilingInformation() {
		// Exit early if no profiling should be done at all
		if ($GLOBALS['T3_VAR']['tx_sqlprofiler']['skipProfiling']) {
			return;
		}

		// Create an entry for the current run
		$runInformation = array(
			'tstamp' => $GLOBALS['EXEC_TIME'],
			'context' => defined('TYPO3_MODE') ? TYPO3_MODE : '',
			'page_id' => $GLOBALS['T3_VAR']['tx_sqlprofiler']['pageId']
		);
		$this->exec_INSERTquery(
			'tx_sqlprofiler_domain_model_run',
			$runInformation
		);
		$runId = $this->sql_insert_id();
		// Create entries for each query and their details
		foreach ($GLOBALS['T3_VAR']['tx_sqlprofiler']['queries'] as $hash => $query) {
			$countDetails = count($GLOBALS['T3_VAR']['tx_sqlprofiler']['queryDetails'][$hash]);
			// Sum execution times to calculate average for the query
			$totalTime = 0;
			for ($i = 0; $i < $countDetails; $i++) {
				$totalTime += $GLOBALS['T3_VAR']['tx_sqlprofiler']['queryDetails'][$hash][$i]['execution_time'];
			}
			$queryInformation = array(
				'run_id' => $runId,
				'query' => $query,
				'count' => $countDetails,
				'average_time' => $totalTime / $countDetails
			);
			$this->exec_INSERTquery(
				'tx_sqlprofiler_domain_model_query',
				$queryInformation
			);
			$queryId = $this->sql_insert_id();
			for ($i = 0; $i < $countDetails; $i++) {
				$queryDetailedInformation = array(
					'query_id' => $queryId,
					'debug_trail' => $GLOBALS['T3_VAR']['tx_sqlprofiler']['queryDetails'][$hash][$i]['debug_trail'],
					'execution_time' => $GLOBALS['T3_VAR']['tx_sqlprofiler']['queryDetails'][$hash][$i]['execution_time']
				);
				$this->exec_INSERTquery(
					'tx_sqlprofiler_domain_model_query_details',
					$queryDetailedInformation
				);
			}
		}


	}
}

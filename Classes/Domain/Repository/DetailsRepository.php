<?php
namespace Cobweb\Sqlprofiler\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Francois Suter (typo3@cobweb.ch)
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

/**
 * Repository for fetching details about a given query.
 *
 * NOTE: this is not a true Extbase repository, as it requires only reading data. Also loading query results
 * into objects would probably be far too costly for the kind of data handled here.
 *
 * @author Francois Suter (Cobweb) <typo3@cobweb.ch>
 * @package TYPO3
 */
class DetailsRepository {
	/**
	 * Fetches all details for a given query, ordered by descending execution time.
	 *
	 * @param integer $query Primary key of a query
	 * @return array|NULL
	 */
	public function findByQuery($query) {
		return $this->getDatabaseConnection()->exec_SELECTgetRows(
			'*',
			'tx_sqlprofiler_domain_model_query_details',
			'query_id = ' . intval($query),
			'',
			'execution_time DESC'
		);
	}

	/**
	 * Wrapper around the global database connection object.
	 *
	 * @return \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	public function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}
}

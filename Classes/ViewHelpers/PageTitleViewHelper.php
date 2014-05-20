<?php
namespace Cobweb\Sqlprofiler\ViewHelpers;

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
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Takes a page number and displays its title and icon.
 *
 * @author Francois Suter (Cobweb) <typo3@cobweb.ch>
 * @package TYPO3
 */
class PageTitleViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
	/**
	 * Given a page id, returns its icon and title (without link or contextual menu).
	 *
	 * @param integer $id Id of a page
	 * @return string The page icon and title
	 */
	public function render($id) {
		$id = intval($id);
		if (!empty($id)) {
			$page = BackendUtility::getRecord('pages', $id);
			// If the page doesn't exist, the result is null, but we need rather an empty array
			if ($page === NULL) {
				$page = array();
			}
			$pageTitle = BackendUtility::getRecordTitle('pages', $page, 1);
			$iconAltText = BackendUtility::getRecordIconAltText($page, 'pages');

			// Create icon for record
			$elementIcon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForRecord('pages', $page, array('title' => $iconAltText));
			return $elementIcon . $pageTitle;
		} else {
			return '';
		}
	}
}

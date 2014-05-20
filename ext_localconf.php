<?php

if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// Xclass the database connection class to log queries
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Core\\Database\\DatabaseConnection'] = array(
	'className' => 'Cobweb\\Sqlprofiler\\Xclass\\DatabaseConnection'
);

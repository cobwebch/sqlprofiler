<?php

########################################################################
# Extension Manager/Repository config file for ext "custom_config".
#
# Auto generated 29-12-2010 17:34
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Query profiler',
	'description' => 'Logs all SQL queries executed by a TYPO3 installation. Provides a BE module for inspection.',
	'category' => 'misc',
	'author' => 'Francois Suter (cobweb)',
	'author_email' => 'typo3@cobweb.ch',
	'shy' => 0,
	'dependencies' => '',
	'conflicts' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.1',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.2.0-6.2.99',
		),
		'conflicts' => array(
			'dbal'
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => '',
	'suggests' => array(
	),
);

?>

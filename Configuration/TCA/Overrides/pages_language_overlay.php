<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$tmp = Array(
	'tx_flseositemap_field' => Array(
		'exclude' => 1,
		'label'   => 'LLL:EXT:fl_seo_sitemap/Rescources/Private/Languages/locallang_db.xlf:pages.fl_seo_sitemap_field_label',
		'config'  => Array(
			'type' => 'input',
			'size' => '30',
		)
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages_language_overlay', $tmp, 1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages_language_overlay', 'tx_flseositemap_field;;;;1-1-1');
?>
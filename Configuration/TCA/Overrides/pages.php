$tmp = Array(
	'tx_flseositemap_field' => Array(
		'exclude' => 1,
		'label'   => 'LLL:EXT:fl_seo_sitemap/Rescources/Private/Languages/locallang_db.xlf:pages.fl_seo_sitemap_field_label",
		'config'  => Array(
			'type' => 'input',
			'size' => '30',
		)
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tmp, 1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', 'tx_flseositemap_field;;;;1-1-1');

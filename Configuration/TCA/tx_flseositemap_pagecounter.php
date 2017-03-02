<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

return Array(
	'ctrl'        => Array(
		'title'             => 'LLL:EXT:fl_seo_sitemap/Resources/Privates/languages/locallang_db.xml:tx_flseositemap_pagecounter',
		'label'             => 'page',
		'tstamp'            => 'tstamp',
		'crdate'            => 'crdate',
		'cruser_id'         => 'cruser_id',
		'default_sortby'    => 'ORDER BY crdate',
		'iconfile'          => 'EXT:fl_seo_sitemap/Resources/Public/Images/icon_tx_flseositemap_pagecounter.gif',
	),
	'interface'   => Array(
		'showRecordFieldList' => 'page,counter'
	),
	'columns'     => Array(
		'page'    => Array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:fl_seo_sitemap/Resources/Privates/languages/locallang_db.xlf:tx_flseositemap_pagecounter.page',
			'config'  => Array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => Array(
					'upper' => '1000',
					'lower' => '10'
				),
				'default'  => 0
			)
		),
		'counter' => Array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:fl_seo_sitemap/Resources/Privates/languages/locallang_db.xlf:tx_flseositemap_pagecounter.counter',
			'config'  => Array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => Array(
					'upper' => '1000',
					'lower' => '10'
				),
				'default'  => 0
			)
		),
	),
	'types'       => Array(
		'0' => Array('showitem' => 'page, counter')
	),
	'palettes'    => Array(
		'1' => Array('showitem' => '')
	)
);
?>
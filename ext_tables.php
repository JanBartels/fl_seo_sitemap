<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

/* Set up the tt_content fields for the frontend plugins */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['fl_seo_sitemap_pi1'] = 'layout,select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['fl_seo_sitemap_pi1'] = 'pi_flexform';
?>
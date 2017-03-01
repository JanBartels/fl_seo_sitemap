<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

/* Set up the tt_content fields for the frontend plugins */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['fl_seo_sitemap_pi1'] = 'layout,select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['fl_seo_sitemap_pi1'] = 'pi_flexform';

/* Add the plugins */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(Array('LLL:EXT:fl_seo_sitemap/Resources/Private/Languages/locallang_db.xlf:tt_content.list_type_pi1','fl_seo_sitemap_pi1'), 'list_type', 'fl_seo_sitemap');

/* Add the flexforms to the TCA */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('fl_seo_sitemap_pi1', 'FILE:EXT:fl_seo_sitemap/Configuration/FlexForms/config.xml');
?>
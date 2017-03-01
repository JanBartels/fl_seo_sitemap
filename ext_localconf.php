<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript('fl_seo_sitemap', 'editorcfg', '
   tt_content.CSS_editor.ch.tx_flseositemap_pi1 = < plugin.tx_flseositemap_pi1.CSS_editor
', 43);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43('fl_seo_sitemap','Classes/Plugin/Pi1.php','_pi1','list_type',1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript('fl_seo_sitemap', 'setup', 'plugin.tx_flseositemap_pi1.userFunc = JBartels\\FlSeoSitemap\\Plugin\\Pi1->main');

/*
** add New CE-wizard elements
*/
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:fl_seo_sitemap/Configuration/PageTS/NewContentElementWizard.ts">'
);
?>
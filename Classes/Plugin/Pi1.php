<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2006 Tim Lochm�ller <tim@fruit-lab.de>
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
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

namespace JBartels\FlSeoSitemap\Plugin;

/**
 * Plugin 'Extended SEO Sitemap' for the 'fl_seo_sitemap' extension.
 *
 * @author   Tim Lochm�ller <tim@fruit-lab.de>
 */
class Pi1 extends \TYPO3\CMS\Frontend\Plugin\AbstractPlugin {

	var $prefixId = 'tx_flseositemap_pi1';

	var $scriptRelPath = 'Classes/Plugin/Pi1.php';

	var $extKey = 'fl_seo_sitemap';

	var $pi_checkCHash = TRUE;

	// function - main
	function main($content, $conf) {

		// Loaddata
		$base = array();
		$base_check = $this->my_getFFvalue('base');

		if (is_array($base_check)) {
			foreach ($base_check as $v) {
				if (trim($v) != '') {
					$base[] = $v;
				}
			}
		}
		if (!sizeof($base)) {
			$base[] = $GLOBALS['TSFE']->id;
		}

		$conf['ext']['deep'] = $this->my_getFFvalue('deep', TRUE);
		$conf['ext']['link_open_in'] = trim($this->my_getFFvalue('link_open_in', TRUE));
		$conf['ext']['seperator'] = $this->my_getFFvalue('seperator', TRUE);
		$conf['ext']['output_type'] = $this->my_getFFvalue('output_type', TRUE);
		$conf['ext']['show_pages'] = $this->my_getFFvalue('show_pages', TRUE);
		$conf['ext']['beschreibung_field'] = $this->my_getFFvalue('beschreibung_field');
		$conf['ext']['link_field'] = $this->my_getFFvalue('link_field');
		$conf['ext']['ignore'] = $this->my_getFFvalue('ignore');
		$conf['ext']['shortcut_follow'] = $this->my_getFFvalue('shortcut_follow', TRUE);
		// Cleanup the list
		$conf['ext']['ignore'] = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', implode(',', $conf['ext']['ignore']), TRUE);

		$conf['ext']['headlines_instead'] = $this->my_getFFvalue('headlines_instead', TRUE);
		$conf['ext']['show_pagecounter'] = $this->my_getFFvalue('show_pagecounter', TRUE);
		$conf['ext']['language'] = $this->my_getFFvalue('language', TRUE);

		$conf['ext']['CSS'] = (str_replace('_class', '', $conf['ext']['output_type']) != $conf['ext']['output_type']);
		$conf['ext']['output_type'] = str_replace('_class', '', $conf['ext']['output_type']);
		$conf['ext']['show_pages'] = $this->generateAddQueryPagesNav($conf['ext']['show_pages']);
		$conf['ext']['usergroup'] = $this->generateAddQueryPagesFEGroup($GLOBALS['TSFE']->fe_user->user['usergroup']);
		if ($conf['ext']['language'] == '' OR !$conf['ext']['language']) {
			$conf['ext']['language'] = FALSE;
		}

		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL('EXT:fl_seo_sitemap/Resources/Private/Languages/locallang.xlf');
		$this->cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');

		foreach ($base as $b) {
			$content .= $this->getListOfPages($b);
		}

		return $this->pi_wrapInBaseClass($content);
	}

	// function

	function countPage() {
		$tab = 'tx_flseositemap_pagecounter';
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('counter,uid', $tab, 'page=' . $GLOBALS['TSFE']->id, '', '');
		$num = $GLOBALS['TYPO3_DB']->sql_num_rows($res);

		if ($num > 0) {
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery($tab, 'page=' . $GLOBALS['TSFE']->id, array('counter' => $row['counter'] + 1));
		} else {
			$GLOBALS['TYPO3_DB']->exec_INSERTquery($tab, array(
				'page'    => $GLOBALS['TSFE']->id,
				'counter' => 1,
				'tstamp'  => time(),
				'crdate'  => time()
			));
		}
		return '';
	}

	// function - countPage
	// function - generateAddQueryPages
	function generateAddQueryPagesNav($in) {

		$std = '(`doktype`=1 AND `nav_hide`=0)';
		$std_nav = '(`doktype`=1 AND `nav_hide`=1)';
		$erw = '(`doktype`=2 AND `nav_hide`=0)';
		$erw_nav = '(`doktype`=2 AND `nav_hide`=1)';
		$dok = '(`doktype`=4 AND `nav_hide`=0)';
		$ext_url = '(`doktype`=3)';

		switch ($in) {
			case 1:
				$out = $std;
				break;
			case 2:
				$out = $std_nav;
				break;
			case 3:
				$out = $std . ' OR ' . $std_nav;
				break;
			case 4:
				$out = $erw;
				break;
			case 5:
				$out = $erw . ' OR ' . $std;
				break;
			case 6:
				$out = $erw . ' OR ' . $std_nav;
				break;
			case 7:
				$out = $erw . ' OR ' . $std . ' OR ' . $std_nav;
				break;
			case 8:
				$out = $erw_nav;
				break;
			case 9:
				$out = $erw_nav . ' OR ' . $std;
				break;
			case 10:
				$out = $erw_nav . ' OR ' . $std_nav;
				break;
			case 11:
				$out = $erw_nav . ' OR ' . $std_nav . ' OR ' . $std;
				break;
			case 12:
				$out = $erw_nav . ' OR ' . $erw;
				break;
			case 13:
				$out = $erw_nav . ' OR ' . $std . ' OR ' . $erw;
				break;
			case 14:
				$out = $erw_nav . ' OR ' . $std_nav . ' OR ' . $erw;
				break;
			case 15:
				$out = $erw_nav . ' OR ' . $std . ' OR ' . $erw . ' OR ' . $std_nav;
				break;
			case 16:
				$out = $dok;
				break;
			case 17:
				$out = $dok . ' OR ' . $std;
				break;
			case 18:
				$out = $dok . ' OR ' . $std_nav;
				break;
			case 19:
				$out = $dok . ' OR ' . $std . ' OR ' . $std_nav;
				break;
			case 20:
				$out = $dok . ' OR ' . $erw;
				break;
			case 21:
				$out = $dok . ' OR ' . $erw . ' OR ' . $std;
				break;
			case 22:
				$out = $dok . ' OR ' . $erw . ' OR ' . $std_nav;
				break;
			case 23:
				$out = $dok . ' OR ' . $erw . ' OR ' . $std . ' OR ' . $std_nav;
				break;
			case 24:
				$out = $dok . ' OR ' . $erw_nav;
				break;
			case 25:
				$out = $dok . ' OR ' . $erw_nav . ' OR ' . $std;
				break;
			case 26:
				$out = $dok . ' OR ' . $erw_nav . ' OR ' . $std_nav;
				break;
			case 27:
				$out = $dok . ' OR ' . $erw_nav . ' OR ' . $std_nav . ' OR ' . $std;
				break;
			case 28:
				$out = $dok . ' OR ' . $erw_nav . ' OR ' . $erw;
				break;
			case 29:
				$out = $dok . ' OR ' . $erw_nav . ' OR ' . $std . ' OR ' . $erw;
				break;
			case 30:
				$out = $dok . ' OR ' . $erw_nav . ' OR ' . $std_nav . ' OR ' . $erw;
				break;
			case 31:
				$out = $dok . ' OR ' . $erw_nav . ' OR ' . $std . ' OR ' . $erw . ' OR ' . $std_nav;
				break;
			case 32:
				$out = $ext_url;
				break;
			case 33:
				$out = $ext_url . ' OR ' . $std;
				break;
			case 34:
				$out = $ext_url . ' OR ' . $std_nav;
				break;
			case 35:
				$out = $ext_url . ' OR ' . $std . ' OR ' . $std_nav;
				break;
			case 36:
				$out = $ext_url . ' OR ' . $erw;
				break;
			case 37:
				$out = $ext_url . ' OR ' . $erw . ' OR ' . $std;
				break;
			case 38:
				$out = $ext_url . ' OR ' . $erw . ' OR ' . $std_nav;
				break;
			case 39:
				$out = $ext_url . ' OR ' . $erw . ' OR ' . $std . ' OR ' . $std_nav;
				break;
			case 40:
				$out = $ext_url . ' OR ' . $erw_nav;
				break;
			case 41:
				$out = $ext_url . ' OR ' . $erw_nav . ' OR ' . $std;
				break;
			case 42:
				$out = $ext_url . ' OR ' . $erw_nav . ' OR ' . $std_nav;
				break;
			case 43:
				$out = $ext_url . ' OR ' . $erw_nav . ' OR ' . $std_nav . ' OR ' . $std;
				break;
			case 44:
				$out = $ext_url . ' OR ' . $erw_nav . ' OR ' . $erw;
				break;
			case 45:
				$out = $ext_url . ' OR ' . $erw_nav . ' OR ' . $std . ' OR ' . $erw;
				break;
			case 46:
				$out = $ext_url . ' OR ' . $erw_nav . ' OR ' . $std_nav . ' OR ' . $erw;
				break;
			case 47:
				$out = $ext_url . ' OR ' . $erw_nav . ' OR ' . $std . ' OR ' . $erw . ' OR ' . $std_nav;
				break;
			case 48:
				$out = $ext_url . ' OR ' . $dok;
				break;
			case 49:
				$out = $ext_url . ' OR ' . $dok . ' OR ' . $std;
				break;
			case 50:
				$out = $ext_url . ' OR ' . $dok . ' OR ' . $std_nav;
				break;
			case 51:
				$out = $ext_url . ' OR ' . $dok . ' OR ' . $std . ' OR ' . $std_nav;
				break;
			case 52:
				$out = $ext_url . ' OR ' . $dok . ' OR ' . $erw;
				break;
			case 53:
				$out = $ext_url . ' OR ' . $dok . ' OR ' . $erw . ' OR ' . $std;
				break;
			case 54:
				$out = $ext_url . ' OR ' . $dok . ' OR ' . $erw . ' OR ' . $std_nav;
				break;
			case 55:
				$out = $ext_url . ' OR ' . $dok . ' OR ' . $erw . ' OR ' . $std . ' OR ' . $std_nav;
				break;
			case 56:
				$out = $ext_url . ' OR ' . $dok . ' OR ' . $erw_nav;
				break;
			case 57:
				$out = $ext_url . ' OR ' . $dok . ' OR ' . $erw_nav . ' OR ' . $std;
				break;
			case 58:
				$out = $ext_url . ' OR ' . $dok . ' OR ' . $erw_nav . ' OR ' . $std_nav;
				break;
			case 59:
				$out = $ext_url . ' OR ' . $dok . ' OR ' . $erw_nav . ' OR ' . $std_nav . ' OR ' . $std;
				break;
			case 60:
				$out = $ext_url . ' OR ' . $dok . ' OR ' . $erw_nav . ' OR ' . $erw;
				break;
			case 61:
				$out = $ext_url . ' OR ' . $dok . ' OR ' . $erw_nav . ' OR ' . $std . ' OR ' . $erw;
				break;
			case 62:
				$out = $ext_url . ' OR ' . $dok . ' OR ' . $erw_nav . ' OR ' . $std_nav . ' OR ' . $erw;
				break;
			case 63:
				$out = $ext_url . ' OR ' . $dok . ' OR ' . $erw_nav . ' OR ' . $std . ' OR ' . $erw . ' OR ' . $std_nav;
				break;
			default:
				$out = '';
				break;
		} // switch

		return '(' . $out . ')';
	}

	// function
	// function - generateAddQueryPages
	function generateAddQueryPagesFEGroup($in) {
		return (trim($in) == '') ? '(`fe_group`=\'\' OR `fe_group`=-1)' : '(`fe_group` IN (' . $in . ') OR `fe_group`=-2 OR `fe_group`=\'\')';
	}

	// function
	// function - getListOfPages
	function getListOfPages($uid, $deep = 0, $c = '', $end = FALSE) {
		// ignores abfangen
		if (in_array($uid, $this->conf['ext']['ignore']) or $deep == $this->conf['ext']['deep'] or $this->conf['ext']['show_pages'] == '') {
			return;
		}

		$where = '`pid`=' . $uid . ' AND ' . $this->conf['ext']['show_pages'] . $this->cObj->enableFields('pages');
		// Ignore ignored pages
		if (is_array($this->conf['ext']['ignore']) && sizeof($this->conf['ext']['ignore']) > 0) {
			$where .= ' AND uid NOT IN (' . implode(',', $this->conf['ext']['ignore']) . ')';
		}

		if ($this->conf['ext']['language'] === FALSE) {
			$where .= ' AND l18n_cfg NOT IN (1,3)';
		}

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,newUntil as neu,tstamp as last,description as beschreibung,abstract as inhaltsangabe,keywords as stichworte,tx_flseositemap_field as seo_sitemap,title as seitentitel,subtitle untertitel,nav_title as navigationstitel, url, urltype, doktype,shortcut AS weiterleitung', 'pages', $where, '', 'sorting');
		$num = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
		$all = $num;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

			// Language Support
			if ($this->conf['ext']['language']) {

				$reslang = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,tstamp as last,description as beschreibung,abstract as inhaltsangabe,keywords as stichworte,tx_flseositemap_field as seo_sitemap,title as seitentitel,subtitle untertitel,nav_title as navigationstitel, url, urltype, shortcut AS weiterleitung', 'pages_language_overlay', 'hidden=0 AND deleted=0 AND sys_language_uid=' . $this->conf['ext']['language'] . ' AND pid=' . $row['uid']);
				$rowlang = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($reslang);
				if ($rowlang) {
					$row['beschreibung'] = $rowlang['beschreibung'];
					$row['inhaltsangabe'] = $rowlang['inhaltsangabe'];
					$row['stichworte'] = $rowlang['stichworte'];
					$row['seo_sitemap'] = $rowlang['seo_sitemap'];
					$row['seitentitel'] = $rowlang['seitentitel'];
					$row['untertitel'] = $rowlang['untertitel'];
					$row['navigationstitel'] = $rowlang['navigationstitel'];
					if (strlen($rowlang['url'])) {
						$row['url'] = $rowlang['url'];
					}
				} else {

					continue;
				}

				// if
			} // if


			if ($c == '') {
				if (($this->conf['ext']['output_type'] == 'singlelist' AND $deep == 0) OR $this->conf['ext']['output_type'] == 'multilist') {
					$c .= '<ul' . $this->css(array(
							'list',
							'deep' . $deep
						)) . '>';
				}
			} // if
			// Beschreibung
			$beschreibung = '';
			for ($i = 0; $i < sizeof($this->conf['ext']['beschreibung_field']) AND $beschreibung == ''; $i++) {
				if (trim($row[$this->conf['ext']['beschreibung_field'][$i]]) != '') {
					$beschreibung = trim($row[$this->conf['ext']['beschreibung_field'][$i]]);
				}
				if (isset($this->conf['description.']['wrap'])) {
					$beschreibung = preg_replace("/\|/", $beschreibung, $this->conf['description.']['wrap']);
				}
			} // for

			if ($this->conf['ext']['headlines_instead']) {
				$beschreibung = '<ul class="headlines">';
				$res_inner2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery('header', 'tt_content', '`deleted`=0 AND `hidden`=0 AND `colPos`=0 AND pid=' . $row['uid'], '', 'sorting');
				while ($row_inner2 = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_inner2)) {
					if ($row_inner2['header'] != '') {
						$beschreibung .= '<li>' . $row_inner2['header'] . '</li>';
					}
				}
				if ($beschreibung == '<ul class="headlines">') {
					$beschreibung = '';
				} else {
					$beschreibung .= '</ul>';
				}
			}

			if ($this->conf['ext']['show_pagecounter']) {
				$res_inner2 = $counter = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($GLOBALS['TYPO3_DB']->exec_SELECTquery('counter', 'tx_flseositemap_pagecounter', '`page`=' . $row['uid']));
				if (isset($counter['counter']) AND trim($counter['counter']) != '') {
					$beschreibung .= ' <span class="sitemap_counter">(' . $counter['counter'] . ' views)</span>';
				}
			} // if
			// Linktext
			$linktext = '';
			for ($i = 0; $i < sizeof($this->conf['ext']['link_field']) AND $linktext == ''; $i++) {
				if (trim($row[$this->conf['ext']['link_field'][$i]]) != '') {
					$linktext = trim($row[$this->conf['ext']['link_field'][$i]]);
				}
				if (isset($this->conf['linktext.']['wrap'])) {
					$linktext = preg_replace("/\|/", $linktext, $this->conf['linktext.']['wrap']);
				}
			} // for

			if ($linktext == '') {
				return "Set the Linktext in the Plugin configuration!";
			}

			$ankerlinkziel = '';
			if ($row['doktype'] == 3) {
				switch ($row['urltype']) {
					case 1:
						$ankerlinkziel .= 'http://';
						break;
					case 2:
						$ankerlinkziel .= 'ftp://';
						break;
					case 3:
						$ankerlinkziel .= 'mailto:';
						break;
					case 4:
						$ankerlinkziel .= 'https://';
						break;
				} // switch
				$ankerlinkziel .= $row['url'];
			} else {
				$ankerlinkziel = $this->pi_getPageLink($row['uid']);
			}

			if ($ankerlinkziel == '') {
				$ankerlinkziel = '/';
			}

			$outstr = '<a href="' . $ankerlinkziel . '"';
			if ($this->conf['ext']['link_open_in'] != '') {
				$outstr .= ' target="' . $this->conf['ext']['link_open_in'] . '"';
			}
			$outstr .= '>' . htmlspecialchars($linktext) . '</a> ' . ((trim($beschreibung) != '' AND !$this->conf['ext']['headlines_instead']) ? $this->conf['ext']['seperator'] : '') . ' ' . $beschreibung . "\n\r";

			#$next = $this->getListOfPages($row['uid'], $deep+1);

			if ($row['doktype'] == 4 && $row['weiterleitung'] != 0 && $this->conf['ext']['shortcut_follow']) #
			{
				$next = $this->getListOfPages($row['weiterleitung'], $deep + 1);
			} else {
				$next = $this->getListOfPages($row['uid'], $deep + 1);
			}


			if ($all == $num) {
				$cssLI = array(
					'list',
					'deep' . $deep,
					'first'
				);
			} elseif ($num == 1) {
				$cssLI = array(
					'list',
					'deep' . $deep,
					'last'
				);
			} else {
				$cssLI = array(
					'item',
					'deep' . $deep
				);
			}

			// Zeit
			$t = time();
			$tag = 24 * 60 * 60;
			if ($t - $tag < $row['last']) {
				$cssLI[] = 'lastDay';
			} elseif ($t - ($tag * 7) < $row['last']) {
				$cssLI[] = 'lastWeek';
			} elseif ($t - ($tag * 30) < $row['last']) {
				$cssLI[] = 'lastMonth';
			} elseif ($t - ($tag * 30 * 2) < $row['last']) {
				$cssLI[] = 'last2Month';
			} elseif ($t - ($tag * 30 * 3) < $row['last']) {
				$cssLI[] = 'last3Month';
			} elseif ($t - ($tag * 30 * 6) < $row['last']) {
				$cssLI[] = 'last6Month';
			} elseif ($t - ($tag * 30 * 12) < $row['last']) {
				$cssLI[] = 'last12Month';
			} else {
				$cssLI[] = 'old';
			}

			if ($row['neu'] > $t) {
				$cssLI[] = 'new';
			}

			// Ausgabe
			$c .= '<li' . $this->css($cssLI) . '>' . $outstr . '' . (($this->conf['ext']['output_type'] != 'singlelist') ? $next : '') . '</li>' . (($this->conf['ext']['output_type'] != 'singlelist') ? '' : $next);

			$end = TRUE;
			$num--;
		} // while

		if ((($this->conf['ext']['output_type'] == 'singlelist' AND $deep == 0) OR $this->conf['ext']['output_type'] == 'multilist') AND $end) {
			$c .= '</ul>';
		}


		return $c;
	}

	// function
	// function - css
	function css($s) {
		if (!$this->conf['ext']['CSS']) {
			return '';
		}
		return (is_array($s)) ? ' class="' . implode(' ', $s) . '"' : ' class="' . $s . '"';
	}

	// function
	// function - my_getFFvalue
	function my_getFFvalue($v, $first = FALSE) {
		if (!$this->my_FFLoad) {
			$this->pi_initPIflexForm();
		}
		$this->my_FFLoad = TRUE;
		$var = explode(',', $this->pi_getFFvalue($this->cObj->data['pi_flexform'], $v, 'sDEF'));
		return ($first) ? $var[0] : $var;
	}

	// function
	// function - err
	function err($err) {
		mail($this->errMail, 'Fehler in ' . $this->extKey, $err);
		return '<div style="border:2px solid red; font-weight: bold; padding: 10px; background-color: #FF9999">' . $err . '</div>';
	}

	// function
	// function - debug
	function debug($a, $t = 'kein Titel') {
		echo "<h1>" . $t . "</h1>";
		var_dump($a);
	}

	// function
}

// class

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fl_seo_sitemap/pi1/class.tx_flseositemap_pi1.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fl_seo_sitemap/pi1/class.tx_flseositemap_pi1.php']);
} // if
?>
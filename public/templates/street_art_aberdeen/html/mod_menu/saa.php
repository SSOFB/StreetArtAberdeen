<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Utilities\ArrayHelper;

echo "<div class=\"saa_menu\">";

foreach ($list as $i => &$item) :

	$itemParams = $item->getParams();
	$class      = [];
	if ($item->id == $default_id) {
		$class[] = 'default';
	}
	if ($item->id == $active_id || ($item->type === 'alias' && $itemParams->get('aliasoptions') == $active_id)) {
		$class[] = 'current';
	}
	if (in_array($item->id, $path)) {
		$class[] = 'active';
	} elseif ($item->type === 'alias') {
		$aliasToId = $itemParams->get('aliasoptions');
		if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
			$class[] = 'active';
		} elseif (in_array($aliasToId, $path)) {
			$class[] = 'alias-parent-active';
		}
	}

	if ($item->type === 'separator') {
		$class[] = 'divider';
	}

	if ($showAll) {
		if ($item->deeper) {
			$class[] = 'deeper';
		}
		if ($item->parent) {
			$class[] = 'parent';
		}
	}
	echo "<div class=\"" . implode(' ', $class) . "\">";

	// Render the menu item.
	switch ($item->type) :
		case 'separator':
		case 'url':
		case 'component':
		case 'heading':
			require JModuleHelper::getLayoutPath('mod_menu', 'saa_'.$item->type);
			break;

		default:
			require JModuleHelper::getLayoutPath('mod_menu', 'saa_url');
			break;
	endswitch;

	echo "</div>";
    
endforeach;

echo "</div>";
?>
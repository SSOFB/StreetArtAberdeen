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

foreach ($list as $i => &$item) :

	$itemParams = $item->getParams();
	
	// Render the menu item.
	switch ($item->type) :
		case 'separator':
		case 'url':
		case 'component':
		case 'heading':
			require JModuleHelper::getLayoutPath('mod_menu', 'flat_'.$item->type);
			break;

		default:
			require JModuleHelper::getLayoutPath('mod_menu', 'flat_url');
			break;
	endswitch;

	echo '&nbsp;&nbsp;&nbsp;&nbsp;';
    
endforeach;
?>

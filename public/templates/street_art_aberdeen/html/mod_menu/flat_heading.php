<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$class = $item->anchor_css ? '<i class="'.$item->anchor_css.'" ></i>' : '';
?>
<span class="nav-header"><?php echo $class; ?><?php echo $item->title; ?></span>
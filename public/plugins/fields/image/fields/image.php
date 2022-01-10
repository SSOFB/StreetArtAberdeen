<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Image
 *
 * @copyright   Copyright (C) 2017 NAME. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('file');

class JFormFieldImage extends JFormFieldFile
{
	public $type = 'Image';
}

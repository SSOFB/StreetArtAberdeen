<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Text
 *
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * 
 * This is the view portion of the field
 */

defined('_JEXEC') or die;


#echo "<pre>" . print_r($field, TRUE) . "</pre>";

$value = $field->value;

if ($value == '')
{
	return;
}

if (is_array($value))
{
	$value = implode(', ', $value);
}

#echo htmlentities($value);

#echo "zzz";

echo "<img class=\"image_field_display\" alt=\"" . $field->label . "\" src=\"" . $value . "\" />";

# TODO: make this render an img tag
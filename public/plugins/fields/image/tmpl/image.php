<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Image
 *
 * @copyright   (C) 2022 SSOFB Ltd
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
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

# TODO: see if we can use the helper files's 'large_image' function to get the name of the large image

# see if we have a smaller one to use
$sized_image = str_replace("images/", "images/large_", $value);


# render an img tag
echo "<img class=\"image_field_display\" alt=\"" . $field->label . "\" src=\"" . $sized_image . "\" />";

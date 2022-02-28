<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   (C) 2020 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;


# get the helper
use Joomla\CMS\Saa_helper\Saa_helper;
JLoader::register('Joomla\CMS\Saa_helper\Saa_helper', 'templates/street_art_aberdeen/html/saa_helper.php'); 

JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');

echo "<div class=\"latest_art\">\n";

foreach ($items as $item) {

	$photo = "";
	$custom_fields = FieldsHelper::getFields('com_content.article', $item, true);
	#echo "<pre>" . print_r($item, TRUE) . "</pre>";
	#echo "<pre>custom_fields:\n" . print_r($custom_fields, TRUE) . "</pre>";
	foreach ( $custom_fields AS $custom_field ) {
		if ( $custom_field->name == "photo" ) {
			$photo = $custom_field->rawvalue;
		}
	}
	#echo "<pre>photo: " . $photo . "</pre>";
	
	if ( $photo ) {
		echo "<div>\n";
		echo "<a href=\"".  Route::_($item->link) . "\">";
		echo "<img src=\"" . Saa_helper::small_image( $photo ) . "\" alt=\"" . $item->title . "\" />";
		echo "</a>\n";
		echo "</div>\n";
	}

}

echo "</div>\n";


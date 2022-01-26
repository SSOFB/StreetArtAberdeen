<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

#namespace J4xdemos\Component\Mywalks\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\AssociationHelper;
use Joomla\Component\Content\Site\Helper\RouteHelper;

# TODO: figure out J4 helper loading
JLoader::register('saa_helper', 'templates/street_art_aberdeen/html/saa_helper.php');
#JLoader::registerPrefix('saa_helper', JPATH_ROOT . '/templates/street_art_aberdeen/html/');
#JLoader::registerNamespace('saa_helper', JPATH_ROOT . '/templates/street_art_aberdeen/html/');

# saa_helper::check_image("image-field-file_id313_2022-01-20_22-32-44_2247.jpeg");


$count = 0;

echo "<div class=\"gallery container-fluid\">";
foreach ($this->items as $i => $article) {
	#echo "<pre>" . print_r($article, TRUE) . "</pre>";

	#$article = $this->items[0];

	#JFactory::getApplication()->enqueueMessage("article id: " . $article->id);

	#if ($count < 5) {
		if ( saa_helper::check_image($article->jcfields[6]->rawvalue) ) {
			echo "<a href=\"".  Route::_(RouteHelper::getArticleRoute($article->slug, $article->catid, $article->language)) . "\">";
			echo "<img src=\"" . saa_helper::small_image( $article->jcfields[6]->rawvalue ) . "\" alt=\"" . $article->title . "\" />";
			echo "</a>\n";
		}
	#}


	$count++;
}
echo "</div>";
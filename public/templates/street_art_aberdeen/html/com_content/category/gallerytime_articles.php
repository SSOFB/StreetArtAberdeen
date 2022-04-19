<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


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

# get the helper
use Joomla\CMS\Saa_helper\Saa_helper;
JLoader::register('Joomla\CMS\Saa_helper\Saa_helper', 'templates/street_art_aberdeen/html/saa_helper.php'); 

#echo "<pre>items: " . count($this->items) . "</pre>";

$prev_date = "";

echo "<div class=\"gallery timeline container-fluid\">";
foreach ($this->items as $i => $article) {

    $this_date = date('l jS \of F Y', strtotime($article->created) );
    if ( $this_date != $prev_date ) {
        echo "<h2>" . $this_date . "</h3>";
    }

	#echo "<pre>" . print_r($article, TRUE) . "</pre>";
    #echo "<pre>created: " . $article->created . "</pre>";
	#JFactory::getApplication()->enqueueMessage("article id: " . $article->id);

	if ( Saa_helper::check_image($article->jcfields[6]->rawvalue) ) {
		echo "<a href=\"".  Route::_(RouteHelper::getArticleRoute($article->slug, $article->catid, $article->language)) . "\">";
		echo "<img src=\"" . Saa_helper::small_image( $article->jcfields[6]->rawvalue ) . "\" alt=\"" . $article->title . "\" />";
		echo "</a>\n";
	}
    $prev_date = $this_date;
}
echo "</div>";
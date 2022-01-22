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


/**
  Map module for CMA Planning
 
  https://ezmap.co/

 */

defined('_JEXEC') or die;

#JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
#JLoader::register('fieldattach', 'components/com_fieldsattach/helpers/fieldattach.php'); 

#57.145428390778264,-2.0937312622943405

?>
<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDmXMhPB4QnspmKY49FP3YnlhRp7_ao1CA'></script>
<script>
  function init() {
    var mapOptions = {
   "center":{
      "lat":57.15293719699627,
      "lng":-2.0985408827160112
   },
   "clickableIcons":true,
   "disableDoubleClickZoom":false,
   "draggable":true,
   "fullscreenControl":true,
   "keyboardShortcuts":true,
   "mapMaker":false,
   "mapTypeControl":true,
   "mapTypeControlOptions":{
      "style":0
   },
   "mapTypeId":"roadmap",
   "rotateControl":true,
   "scaleControl":true,
   "scrollwheel":true,
   "streetViewControl":true,
   "styles":[
      {
         "featureType":"administrative",
         "elementType":"labels.text.fill",
         "stylers":[
            {
               "color":"#444444"
            }
         ]
      },
      {
         "featureType":"administrative.country",
         "elementType":"geometry.stroke",
         "stylers":[
            {
               "weight":"0.5"
            }
         ]
      },
      {
         "featureType":"landscape",
         "elementType":"all",
         "stylers":[
            {
               "color":"#f2f2f2"
            }
         ]
      },
      {
         "featureType":"landscape.man_made",
         "elementType":"geometry.fill",
         "stylers":[
            {
               "color":"#d3d2d1"
            }
         ]
      },
      {
         "featureType":"landscape.natural",
         "elementType":"geometry.fill",
         "stylers":[
            {
               "visibility":"on"
            },
            {
               "color":"#eae8e4"
            }
         ]
      },
      {
         "featureType":"landscape.natural",
         "elementType":"labels.text.fill",
         "stylers":[
            {
               "color":"#a06845"
            },
            {
               "weight":"10"
            }
         ]
      },
      {
         "featureType":"landscape.natural",
         "elementType":"labels.text.stroke",
         "stylers":[
            {
               "weight":"4"
            }
         ]
      },
      {
         "featureType":"landscape.natural",
         "elementType":"labels.icon",
         "stylers":[
            {
               "visibility":"off"
            },
            {
               "color":"#9c7359"
            }
         ]
      },
      {
         "featureType":"landscape.natural.landcover",
         "elementType":"all",
         "stylers":[
            {
               "visibility":"on"
            },
            {
               "color":"#dddcdb"
            }
         ]
      },
      {
         "featureType":"landscape.natural.terrain",
         "elementType":"geometry.fill",
         "stylers":[
            {
               "visibility":"on"
            },
            {
               "color":"#dddcdb"
            }
         ]
      },
      {
         "featureType":"poi",
         "elementType":"all",
         "stylers":[
            {
               "visibility":"off"
            }
         ]
      },
      {
         "featureType":"road",
         "elementType":"all",
         "stylers":[
            {
               "saturation":-100
            },
            {
               "lightness":45
            }
         ]
      },
      {
         "featureType":"road.highway",
         "elementType":"all",
         "stylers":[
            {
               "visibility":"simplified"
            }
         ]
      },
      {
         "featureType":"road.highway",
         "elementType":"labels.icon",
         "stylers":[
            {
               "visibility":"off"
            }
         ]
      },
      {
         "featureType":"road.arterial",
         "elementType":"labels.icon",
         "stylers":[
            {
               "visibility":"off"
            },
            {
               "weight":"0.01"
            }
         ]
      },
      {
         "featureType":"transit",
         "elementType":"all",
         "stylers":[
            {
               "visibility":"off"
            }
         ]
      },
      {
         "featureType":"water",
         "elementType":"all",
         "stylers":[
            {
               "color":"#6084b9"
            },
            {
               "visibility":"on"
            }
         ]
      }
   ],
   "zoom":15,
   "zoomControl":true,
   "navigationControl":true,
   "navigationControlOptions":{
      "style":1
   }
};
    var mapElement = document.getElementById('ez-map');
    var map = new google.maps.Map(mapElement, mapOptions);
<?php
JLoader::register('saa_helper', 'templates/street_art_aberdeen/html/saa_helper.php');
JHtml::_('jquery.framework');

foreach ($this->items as $i => $article) {
    #echo $article->title;
    #echo $article->introtext;
    #echo $article->link;
    
    # TODO: add image
    # TODO: strip tags from trimmed_text
    
    #$lat = fieldattach::getValue($article->id, 5);
    #$lon = fieldattach::getValue($article->id, 6);
	list($lat, $lon) = explode(",", $article->jcfields[2]->rawvalue);
    
    #$trimmed_text = $article->introtext;
    /*
    rem Nov 2021
         0 strpos(): Argument #3 ($offset) must be contained in argument #1 ($haystack) 
    $pos = strpos($trimmed_text, ' ', 500);
    if ( $pos ){
        $trimmed_text = substr($trimmed_text, 0, $pos); 
    }
    */
    
    $info_window_content = "<a href=\"".  Route::_(RouteHelper::getArticleRoute($article->slug, $article->catid, $article->language)) . "\">";
	$info_window_content .=  "<img src=\"" . saa_helper::small_image( $article->jcfields[6]->rawvalue ) . "\" alt=\"" . $article->title . "\" />";
	$info_window_content .=  "</a>\n";

    $info_window_content = json_encode($info_window_content);
    
    
    if ( $lat AND $lon ) {
        ?>
        var marker<?php echo $article->id; ?> = new google.maps.Marker({
			position: {lat:<?php echo $lat; ?>, lng: <?php echo $lon; ?>}, 
			map: map,
			icon: {
				url: "<?php echo saa_helper::small_image( $article->jcfields[6]->rawvalue ); ?>", 
				scaledSize: new google.maps.Size(30, 30),
			}
		});
        var infowindow<?php echo $article->id; ?> = new google.maps.InfoWindow({
			content: <?php echo $info_window_content; ?> ,map: map
		});
        marker<?php echo $article->id; ?>.addListener('click', function () { 
			infowindow<?php echo $article->id; ?>.open(map, marker<?php echo $article->id; ?>) ;
		});
		infowindow<?php echo $article->id; ?>.close();        
        <?php
    }    
}

/*
var marker = new google.maps.Marker({
	position: {lat: 48.856259, lng: 2.365043},
	map: map,
	title: 'PEPSized Coffee',
	icon: {
		url: "images/markers/svg/Coffee_3.svg",
		scaledSize: new google.maps.Size(64, 64)
	}
});
*/
?>

    google.maps.event.addDomListener(window, "resize", function() { var center = map.getCenter(); google.maps.event.trigger(map, "resize"); map.setCenter(center); });
  }
google.maps.event.addDomListener(window, 'load', init);
</script>
<style>
#ez-map{
	min-height:150px; 
	min-width:150px; 
	height: 850px; 
	width: 100%;
	margin-top: -100px
}
</style>
<div id='ez-map'></div>   
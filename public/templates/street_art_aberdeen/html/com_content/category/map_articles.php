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
#echo Saa_helper::tester("galopin");

# mix em up
shuffle($this->items);

?>
<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDmXMhPB4QnspmKY49FP3YnlhRp7_ao1CA'></script>
<script>
let map, infoWindowMyLocation;
function init() {var mapOptions={"center":{"lat":57.15293719699627,"lng":-2.0985408827160112},"clickableIcons":true,"disableDoubleClickZoom":false,"draggable":true,"fullscreenControl":false,"keyboardShortcuts":true,"mapMaker":false,"mapTypeControl":false,"mapTypeControlOptions":{"style":0},"mapTypeId":"roadmap","rotateControl":true,"scaleControl":true,"scrollwheel":true,"streetViewControl":true,"streetViewControlOptions":{"position":google.maps.ControlPosition.RIGHT_CENTER,},"zoom":15,"zoomControl":true,"zoomControlOptions":{"position":google.maps.ControlPosition.RIGHT_CENTER,},"navigationControl":true,"navigationControlOptions":{"style":1},"styles":[{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"administrative.country","elementType":"geometry.stroke","stylers":[{"weight":"0.5"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#d3d2d1"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#eae8e4"}]},{"featureType":"landscape.natural","elementType":"labels.text.fill","stylers":[{"color":"#a06845"},{"weight":"10"}]},{"featureType":"landscape.natural","elementType":"labels.text.stroke","stylers":[{"weight":"4"}]},{"featureType":"landscape.natural","elementType":"labels.icon","stylers":[{"visibility":"off"},{"color":"#9c7359"}]},{"featureType":"landscape.natural.landcover","elementType":"all","stylers":[{"visibility":"on"},{"color":"#dddcdb"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#dddcdb"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"},{"weight":"0.01"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#6084b9"},{"visibility":"on"}]}]};
   var mapElement = document.getElementById('saa-map');
   var map = new google.maps.Map(mapElement, mapOptions);
<?php
JHtml::_('jquery.framework');
# loop through the places
foreach ($this->items as $i => $article) {
   #echo "\n<!-- \n" . print_r($article, TRUE) . "\n -->\n";
   # if it's not gone
   if ( $article->jcfields[9]->rawvalue != "Gone" ) {
      # get the lat and lon
      list($lat, $lon) = explode(",", $article->jcfields[2]->rawvalue);
      # get the image filename
      $small_image = Saa_helper::small_image( $article->jcfields[6]->rawvalue );
      # get the width and height
      list($width, $height) = getimagesize(JPATH_BASE . $small_image);
      # make the link
      $info_window_content  = "<a href=\"".  Route::_(RouteHelper::getArticleRoute($article->slug, $article->catid, $article->language)) . "\">";
      # set the image
      $info_window_content .= "<img loading=\"lazy\" width=\"" . $width . "\" height=\"" . $height . "\" src=\"" . $small_image . "\" alt=\"" . $article->title . "\" />";
      # close the a tag
      $info_window_content .= "</a>\n";
      # make it js safe
      $info_window_content = json_encode($info_window_content);
       
      if ( $lat AND $lon AND $article->state ) {
         # got lat and lon, and published, so stick it on the map
         ?>
      var marker<?php echo $article->id; ?> = new google.maps.Marker({ position: {lat:<?php echo $lat; ?>, lng: <?php echo $lon; ?>}, map: map, icon: { url: "<?php echo Saa_helper::pin_image( $article->jcfields[6]->rawvalue ); ?>", scaledSize: new google.maps.Size(60, 60),} });
      var infowindow<?php echo $article->id; ?> = new google.maps.InfoWindow({ content: <?php echo $info_window_content; ?> ,map: map });
      marker<?php echo $article->id; ?>.addListener('click', function () { infowindow<?php echo $article->id; ?>.open(map, marker<?php echo $article->id; ?>);});
      infowindow<?php echo $article->id; ?>.close();        
         <?php
      }   
   }
}
?>
   markerMyLocation = new google.maps.Marker();
   const locationButton = document.createElement("button");
   locationButton.textContent = "Go to your current location";
   locationButton.classList.add("custom-map-control-button");
   locationButton.classList.add("btn");
   locationButton.classList.add("btn-primary");
   map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(locationButton);
   locationButton.addEventListener("click", () => {
      // Try HTML5 geolocation.
      if (navigator.geolocation) {
         navigator.geolocation.getCurrentPosition(
            (position) => {
               const pos = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude,
               };
               map.setCenter(pos);
               markerMyLocation.setPosition(pos);   
               markerMyLocation.setMap(map);      
            },
            () => {
               handleLocationError(true, markerMyLocation, map.getCenter());
            }
         );
      } else {
         // Browser doesn't support Geolocation
         handleLocationError(false, markerMyLocation, map.getCenter());
      }
   });
};

function handleLocationError(browserHasGeolocation, markerMyLocation, pos) {
   markerMyLocation.setPosition(pos);
   markerMyLocation.setContent(
      browserHasGeolocation
      ? "Error: The Geolocation service failed."
      : "Error: Your browser doesn't support geolocation."
   );
   markerMyLocation.setMap(map);
};

google.maps.event.addDomListener(window, "resize", function() { 
   var center = map.getCenter(); 
   google.maps.event.trigger(map, "resize"); 
   map.setCenter(center); 
});

google.maps.event.addDomListener(window, 'load', init);
</script>
<div id='saa-map'></div>   
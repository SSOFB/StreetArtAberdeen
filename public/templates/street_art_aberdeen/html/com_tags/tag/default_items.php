<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Tags\Site\Helper\RouteHelper;

# get the helper
use Joomla\CMS\Saa_helper\Saa_helper;
JLoader::register('Joomla\CMS\Saa_helper\Saa_helper', 'templates/street_art_aberdeen/html/saa_helper.php'); 

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('com_tags.tag-default');

// Get the user object.
$user = Factory::getUser();

$map_data = Array();

// Check if user is allowed to add/edit based on tags permissions.
// Do we really have to make it so people can see unpublished tags???
$canEdit      = $user->authorise('core.edit', 'com_tags');
$canCreate    = $user->authorise('core.create', 'com_tags');
$canEditState = $user->authorise('core.edit.state', 'com_tags');
?>
<p>All the items with this label...</p>
<div class="com-tags__items">
	<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
		<?php if ($this->params->get('filter_field') || $this->params->get('show_pagination_limit')) : ?>
			<?php if ($this->params->get('filter_field')) : ?>
				<div class="com-tags-tags__filter btn-group">
					<label class="filter-search-lbl visually-hidden" for="filter-search">
						<?php echo Text::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>
					</label>
					<input
						type="text"
						name="filter-search"
						id="filter-search"
						value="<?php echo $this->escape($this->state->get('list.filter')); ?>"
						class="inputbox" onchange="document.adminForm.submit();"
						placeholder="<?php echo Text::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>"
					>
					<button type="submit" name="filter_submit" class="btn btn-primary"><?php echo Text::_('JGLOBAL_FILTER_BUTTON'); ?></button>
					<button type="reset" name="filter-clear-button" class="btn btn-secondary"><?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?></button>
				</div>
			<?php endif; ?>
			<?php if ($this->params->get('show_pagination_limit')) : ?>
				<div class="btn-group float-end">
					<label for="limit" class="visually-hidden">
						<?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?>
					</label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			<?php endif; ?>

			<input type="hidden" name="limitstart" value="">
			<input type="hidden" name="task" value="">
		<?php endif; ?>
	</form>

	<?php if (empty($this->items)) : ?>
		<div class="alert alert-info">
			<span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
			<?php echo Text::_('COM_TAGS_NO_ITEMS'); ?>
		</div>
	<?php else : ?>
		<div class="gallery tag-gallery">
			<?php foreach ($this->items as $i => $item) : ?>
				<?php #echo "<pre>" . print_r($item, TRUE) . "</pre>"; ?>
				<?php 
				if ( Saa_helper::check_image($item->jcfields[6]->rawvalue) ) {
					echo "<a href=\"".  Route::_($item->link) . "\">";
					echo "<img src=\"" . Saa_helper::small_image( $item->jcfields[6]->rawvalue ) . "\" alt=\"" . $item->title . "\" />";
					echo "</a>\n";

					list($lat, $lon) = explode(",", $item->jcfields[2]->rawvalue);

					$info_window_content = "<a href=\"".  Route::_($item->link) . "\">";
					$info_window_content .= "<img src=\"" . Saa_helper::small_image( $item->jcfields[6]->rawvalue ) . "\" alt=\"" . $item->title . "\" />";
					$info_window_content .= "</a>\n";
				    #$info_window_content = json_encode($info_window_content);

					$map_pin = Array(
						"id" => $item->id,
						"lat" => $lat,
						"lon" => $lon,
						"pin_image" => Saa_helper::pin_image( $item->jcfields[6]->rawvalue ),
						"info_window_content" => $info_window_content,

					);
					$map_data[] = $map_pin;
 				}
				?>
			<?php endforeach; ?>
		</div>
<?php 
#echo "<pre>" . print_r($map_data, TRUE) . "</pre>";
?>

<script>

function init() {
      var mapOptions = {
		"center":{
			"lat":57.15293719699627,
			"lng":-2.0985408827160112
		},
		"streetViewControl":false,
		"zoom":15,
   };
   var mapElement = document.getElementById('saa-tag-map');
   var map = new google.maps.Map(mapElement, mapOptions);
   var bounds = new google.maps.LatLngBounds();
<?php
JHtml::_('jquery.framework');
# loop through the places
foreach ($map_data as $i => $map_pin) {
    
   if ( $map_pin["lat"] AND $map_pin["lon"] ) {
      ?>

   var marker<?php echo $map_pin["id"]; ?> = new google.maps.Marker({
      position: {lat:<?php echo $map_pin["lat"]; ?>, lng: <?php echo $map_pin["lon"]; ?>}, 
      map: map,
      icon: {
         url: "<?php echo $map_pin["pin_image"]; ?>", 
         scaledSize: new google.maps.Size(60, 60),
      }
   });
   bounds.extend(marker<?php echo $map_pin["id"]; ?>.position);
   var infowindow<?php echo $map_pin["id"]; ?> = new google.maps.InfoWindow({
      content: <?php echo json_encode( $map_pin["info_window_content"] ); ?> ,map: map
   });
   marker<?php echo $map_pin["id"]; ?>.addListener('click', function () { 
      infowindow<?php echo $map_pin["id"]; ?>.open(map, marker<?php echo $map_pin["id"]; ?>) ;
   });
   infowindow<?php echo $map_pin["id"]; ?>.close();        
      <?php
   }    
}

?>
	map.fitBounds(bounds);

	markerMyLocation = new google.maps.Marker();
	const locationButton = document.createElement("button");
	locationButton.textContent = "Go to your current location";
	locationButton.classList.add("custom-map-control-button");
	locationButton.classList.add("btn");
	locationButton.classList.add("btn-primary");
	map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(locationButton);
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

<h3>Where these are...</h3>
<div id='saa-tag-map'></div>   



		
	<?php endif; ?>
</div>

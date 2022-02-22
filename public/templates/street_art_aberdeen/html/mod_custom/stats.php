<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   (C) 2020 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>


<div class="mod-custom custom banner-overlay" <?php if ($params->get('backgroundimage')) : ?> style="background-image:url(<?php echo $params->get('backgroundimage'); ?>)"<?php endif; ?> >
	<div class="overlay">
		<?php echo $module->content; ?>

<?php


$db = JFactory::getDbo();

# get the total
$query = $db
    ->getQuery(true)
    ->select('COUNT(*)')
    ->from($db->quoteName('#__content'))
    ->where($db->quoteName('catid') . " = 9")
    ->where($db->quoteName('state') . " = 1");
$db->setQuery($query);
$count = $db->loadResult();
echo "<p>Number of artworks: " . $count . "</p>";


# get the added-per-month
$query = $db
    ->getQuery(true)
    ->select('DATE_FORMAT(created, "%M %Y") AS date, count(*) AS total')
    ->from($db->quoteName('#__content'))
    ->where($db->quoteName('catid') . " = 9")
    ->where($db->quoteName('state') . " = 1")
    ->group('date');
$db->setQuery($query);
$counts_data_array = $db->loadObjectList();
#echo "<p>" . $query . "</p>";
#echo "<pre>" . print_r($counts_data_array, TRUE) . "</pre>";
foreach ($counts_data_array AS $counts_data) {
    echo "<p>Added in " . $counts_data->date . ": " . $counts_data->total . "</p>";
}


# TODO: Add user leader board
# TODO: Add visitors
# TODO: Add top items

?>
	</div>
</div>

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
$query = $db
    ->getQuery(true)
    ->select('COUNT(*)')
    ->from($db->quoteName('#__content'))
    ->where($db->quoteName('catid') . " = 9")
    ->where($db->quoteName('state') . " = 1");
$db->setQuery($query);
$count = $db->loadResult();

echo "<p>Number of artworks: " . $count . "</p>";



$query = $db
    ->getQuery(true)
    ->select('COUNT(*)')
    ->from($db->quoteName('#__content'))
    ->where($db->quoteName('catid') . " = 9")
    ->where($db->quoteName('state') . " = 1")
    ->where($db->quoteName('state') . " = 1")
    ->where("MONTH(created) = MONTH(CURRENT_DATE()")
    ->where("YEAR(created) = YEAR(CURRENT_DATE()");
$db->setQuery($query);
$count = $db->loadResult();

echo "<p>Number added this month: " . $count . "</p>";


?>
	</div>
</div>

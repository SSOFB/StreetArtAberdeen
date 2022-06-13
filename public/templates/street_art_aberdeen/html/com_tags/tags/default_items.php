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

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('com_tags.tags-default');

// Get the user object.
$user = Factory::getUser();

// Check if user is allowed to add/edit based on tags permissions.
$canEdit      = $user->authorise('core.edit', 'com_tags');
$canCreate    = $user->authorise('core.create', 'com_tags');
$canEditState = $user->authorise('core.edit.state', 'com_tags');

$columns = $this->params->get('tag_columns', 1);

// Avoid division by 0 and negative columns.
if ($columns < 1)
{
	$columns = 1;
}

$bsspans = floor(12 / $columns);

if ($bsspans < 1)
{
	$bsspans = 1;
}

# setup letter check
$prev_letter = "";


#echo "\n\n<!--\n\n" . print_r($this->items, TRUE) . "\n\n-->\n\n";
?>

<div class="com-tags__items">
	<p>A list of labels.  Labels are used to group together pieces by festival, artist or site.</p>


	<?php if ($this->items == false || $n === 0) : ?>
		<div class="alert alert-info">
			<span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
			<?php echo Text::_('COM_TAGS_NO_TAGS'); ?>
		</div>
	<?php else : ?>
		<div class="tag_col">
			<div>
		<?php foreach ($this->items as $i => $item) : ?>

			<?php 
			$first_letter = strtoupper( substr($item->title, 0, 1) );
			if ( $first_letter != $prev_letter ) {
				if ( $first_letter == "F" OR $first_letter == "M" OR $first_letter == "Q" ) {
					echo "</div>\n<div>";
				}
				echo "<h3>" . $first_letter . "</h3>";
			}
			$prev_letter = $first_letter;
			?>
			
			<?php if ((!empty($item->access)) && in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
				<p>
					<a href="<?php echo Route::_(RouteHelper::getTagRoute($item->id . ':' . $item->alias)); ?>">
						<?php echo $this->escape($item->title); ?>
					</a>
				</p>
			<?php endif; ?>


		<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php // Add pagination links ?>
	<?php if (!empty($this->items)) : ?>
		<?php if (($this->params->def('show_pagination', 2) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
			<div class="com-tags__pagination w-100">
				<?php if ($this->params->def('show_pagination_results', 1)) : ?>
					<p class="counter float-end pt-3 pe-2">
						<?php echo $this->pagination->getPagesCounter(); ?>
					</p>
				<?php endif; ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>

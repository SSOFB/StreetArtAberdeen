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

// Check if user is allowed to add/edit based on tags permissions.
// Do we really have to make it so people can see unpublished tags???
$canEdit      = $user->authorise('core.edit', 'com_tags');
$canCreate    = $user->authorise('core.create', 'com_tags');
$canEditState = $user->authorise('core.edit.state', 'com_tags');
?>
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
		<div class="gallery container-fluid">
			<?php foreach ($this->items as $i => $item) : ?>
				<?php #echo "<pre>" . print_r($item, TRUE) . "</pre>"; ?>
				<?php 
				if ( Saa_helper::check_image($item->jcfields[6]->rawvalue) ) {
					echo "<a href=\"".  Route::_($item->link) . "\">";
					echo "<img src=\"" . Saa_helper::small_image( $item->jcfields[6]->rawvalue ) . "\" alt=\"" . $item->title . "\" />";
					echo "</a>\n";
				}
				?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

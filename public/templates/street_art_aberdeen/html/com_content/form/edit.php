<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate')
	->useScript('com_content.form-edit');

$this->tab_name = 'com-content-form';
$this->ignore_fieldsets = array('image-intro', 'image-full', 'jmetadata', 'item_associations');
$this->useCoreUI = true;

// Create shortcut to parameters.
$params = $this->state->get('params');


#JHTML::script('edit.js', '/templates/street_art_aberdeen/js');
HTMLHelper::_('script', 'templates/street_art_aberdeen/js/edit.js');

// This checks if the editor config options have ever been saved. If they haven't they will fall back to the original settings.
$editoroptions = isset($params->show_publishing_options);

if (!$editoroptions)
{
	$params->show_urls_images_frontend = '0';
}
?>
<div class="edit item-page saa_edit_screen">
	<?php if ($params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1>
			<?php echo $this->escape($params->get('page_heading')); ?>
		</h1>
	</div>
	<?php endif; ?>

	<form action="<?php echo Route::_('index.php?option=com_content&a_id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical" >
		<fieldset>
			


			<?php if ($this->captchaEnabled) : ?>
				<?php echo $this->form->renderField('captcha'); ?>
			<?php endif; ?>

			<?php echo LayoutHelper::render('joomla.edit.params', $this); ?>

			<div class="noshow">
				<?php echo $this->form->renderField('alias'); ?>
				<?php echo $this->form->renderField('transition'); ?>
				<?php echo $this->form->renderField('state'); ?>
				<?php echo $this->form->renderField('catid'); ?>
				<?php echo $this->form->renderField('note'); ?>
				<?php if ($params->get('save_history', 0)) : ?>
					<?php echo $this->form->renderField('version_note'); ?>
				<?php endif; ?>
				<?php if ($params->get('show_publishing_options', 1) == 1) : ?>
					<?php echo $this->form->renderField('created_by_alias'); ?>
				<?php endif; ?>
				<?php if ($this->item->params->get('access-change')) : ?>
					<?php echo $this->form->renderField('featured'); ?>
					<?php if ($params->get('show_publishing_options', 1) == 1) : ?>
						<?php echo $this->form->renderField('featured_up'); ?>
						<?php echo $this->form->renderField('featured_down'); ?>
						<?php echo $this->form->renderField('publish_up'); ?>
						<?php echo $this->form->renderField('publish_down'); ?>
					<?php endif; ?>
				<?php endif; ?>
				<?php echo $this->form->renderField('access'); ?>
				<?php if (is_null($this->item->id)) : ?>
					<div class="control-group">
						<div class="control-label">
						</div>
						<div class="controls">
							<?php echo Text::_('COM_CONTENT_ORDERING'); ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ($params->get('show_publishing_options', 1) == 1) : ?>
						<?php echo $this->form->renderField('metadesc'); ?>
						<?php echo $this->form->renderField('metakey'); ?>
				<?php endif; ?>
			</div>

			<?php echo $this->form->renderField('title'); ?>

			<div class="article_field">
				<p>Notes</p>
				<?php echo $this->form->renderField('articletext'); ?>
			</div>
			<?php echo $this->form->renderField('tags'); ?>

			<input type="hidden" name="task" value="">
			<input type="hidden" name="return" value="<?php echo $this->return_page; ?>">
			<?php echo HTMLHelper::_('form.token'); ?>

			<div class="edit_button_box">
				<button type="button" class="btn btn-primary" data-submit-task="article.save">
					<span class="icon-check" aria-hidden="true"></span>
					<?php echo Text::_('JSAVE'); ?>
				</button>
				<button type="button" class="btn btn-danger" data-submit-task="article.cancel">
					<span class="icon-times" aria-hidden="true"></span>
					<?php echo Text::_('JCANCEL'); ?>
				</button>
				<?php if ($params->get('save_history', 0) && $this->item->id) : ?>
					<?php echo $this->form->getInput('contenthistory'); ?>
				<?php endif; ?>
			</div>			
		</fieldset>

	</form>
</div>
<script type="text/javascript">
var title = $('#jform_title').val();
//alert(title);
if ( title.length == 0 ) {
	$("#jform_title").val( Math.floor(Math.random() * (999 - 100 + 1) + 100) );
}
</script>
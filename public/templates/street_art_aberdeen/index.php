<?php
/**
 * @package     street_art_aberdeen
 *
 * @copyright   (C) 2022 SSOFB. <https://www.ssofb.co.uk>
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

# get the helper
use Joomla\CMS\Saa_helper\Saa_helper;
JLoader::register('Joomla\CMS\Saa_helper\Saa_helper', 'templates/street_art_aberdeen/html/saa_helper.php'); 
Saa_helper::ilog("Hit at template root");
Saa_helper::ilog("Post: " . print_r($_POST, TRUE) );
Saa_helper::ilog("Get: " . print_r($_GET, TRUE) );
Saa_helper::ilog("Files: " . print_r($_FILES, true));
Saa_helper::ilog("Headers: " . print_r(getallheaders(), true));
Saa_helper::ilog("Headers: " . print_r(getallheaders(), true));


/** @var Joomla\CMS\Document\HtmlDocument $this */

$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();

# Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', 'saa_icon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
$this->addHeadLink(HTMLHelper::_('image', 'saa_icon.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);

# Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu     = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';

# Template path
$templatePath = 'templates/' . $this->template;

# Enable assets
$wa->usePreset('template.street_art_aberdeen.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
	->useStyle('template.active.language')
	->useStyle('template.user')
	->useScript('template.user');

# Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.street_art_aberdeen.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

# Defer font awesome
$wa->getAsset('style', 'fontawesome')->setAttribute('rel', 'lazy-stylesheet');

# sidebar
#$got_sidebar = ($this->countModules('sidebar', true) == true ? true : false);

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
	<link rel='author' href='https://plus.google.com/+AndyGaskellUK' />
</head>

<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($pageclass ? ' ' . $pageclass : '')
	. ($this->direction == 'rtl' ? ' rtl' : '');
?>">
	<header class="header container-header full-width position-sticky sticky-top">
		<?php if ($this->countModules('menu', true) || $this->countModules('search', true)) : ?>
			<div class="grid-child container-nav">
				<a class="head_logo_link" href="<?php echo $this->baseurl; ?>" title="<?php echo $app->getCfg( 'sitename' ); ?>">
					Street Art Aberdeen
				</a> 
				<?php if ($this->countModules('menu', true)) : ?>
					<jdoc:include type="modules" name="menu" style="none" />
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</header>

	<main>
		<jdoc:include type="message" />
		<jdoc:include type="modules" name="component_above" style="container" />
		<jdoc:include type="component" />
		<jdoc:include type="modules" name="component_below" style="container" />
	</main>

	<?php if ( $this->countModules('footer_left', true) || $this->countModules('footer_right', true) || $this->countModules('footer_middle', true)  ) : ?>
	<footer class="container-footer footer full-width fixed-bottom">
		<div class="grid-child">
			<div class="pull-left footer_left">
				<jdoc:include type="modules" name="footer_left" style="none" />
			</div>
			<div class="pull-right footer_right">
				<jdoc:include type="modules" name="footer_right" style="none" />
			</div>
		</div>
		<div class="grid-child">
			<div class="footer_middle">
				<jdoc:include type="modules" name="footer_middle" style="none" />
			</div>
		</div>
	</footer>
	<?php endif; ?>

	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
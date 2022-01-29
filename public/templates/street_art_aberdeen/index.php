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

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu     = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';

// Template path
$templatePath = 'templates/' . $this->template;

// Enable assets
$wa->usePreset('template.street_art_aberdeen.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
	->useStyle('template.active.language')
	->useStyle('template.user')
	->useScript('template.user');

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.street_art_aberdeen.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

// Defer font awesome
$wa->getAsset('style', 'fontawesome')->setAttribute('rel', 'lazy-stylesheet');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
	<link rel='author' href='https://plus.google.com/+AndyGaskellUK' />
    <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />    
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/apple-touch-icon-57-precomposed.png"> 
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
		<jdoc:include type="component" />
	</main>

	<?php if ( $this->countModules('footer_left', true) || $this->countModules('footer_right', true) ) : ?>
	<footer class="container-footer footer full-width fixed-bottom">
		<div class="grid-child">
			<div class="pull-left footer_left">
				<jdoc:include type="modules" name="footer_left" style="none" />
			</div>
			<div class="pull-right footer_right">
				<jdoc:include type="modules" name="footer_right" style="none" />
			</div>
		</div>
	</footer>
	<?php endif; ?>



	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
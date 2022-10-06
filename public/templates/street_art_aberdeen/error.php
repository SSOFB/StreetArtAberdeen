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

/** @var Joomla\CMS\Document\ErrorDocument $this */

# log the error stuff
use Joomla\CMS\Saa_helper\Saa_helper;
JLoader::register('Joomla\CMS\Saa_helper\Saa_helper', 'templates/street_art_aberdeen/html/saa_helper.php'); 
Saa_helper::elog("Hit on Joomla error screen");
Saa_helper::elog("Post: " . print_r($_POST, TRUE) );
Saa_helper::elog("Get: " . print_r($_GET, TRUE) );
Saa_helper::elog("Files: " . print_r($_FILES, true));
Saa_helper::elog("Headers: " . print_r(getallheaders(), true));

$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();

// Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', 'splash_simple.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
$this->addHeadLink(HTMLHelper::_('image', 'splash_simple.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);

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
$template_path = '/templates/' . $this->template;

// Enable assets
$wa->usePreset('template.street_art_aberdeen.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
	->useStyle('template.active.language')
	->useStyle('template.user')
	->useScript('template.user');

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.street_art_aberdeen.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

# check the user
$user = Factory::getUser();
$is_user_super = in_array(8, $user->groups );

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
	<link rel="manifest" href="manifest.json">
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-CHHW9DCMR5"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'G-CHHW9DCMR5');
	</script>
</head>

<body class="site error_site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ' ' . $pageclass;
	echo ($this->direction == 'rtl' ? ' rtl' : '');
?>">
	<header class="header container-header full-width position-sticky sticky-top">
		<?php if ($this->countModules('menu', true) || $this->countModules('search', true)) : ?>
			<div class="grid-child container-nav">
				<a class="head_logo_link" href="<?php echo $this->baseurl; ?>" title="<?php echo $app->getCfg( 'sitename' ); ?>">
					<img src="<?php echo $template_path; ?>/images/splash_simple.svg" alt="Street Art Aberdeen splash logo" />
					<div>Street Art Aberdeen</div>
				</a> 
				<?php if ($this->countModules('menu', true)) : ?>
					<jdoc:include type="modules" name="menu" style="none" />
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</header>

	<main class="container-component">
		<div class="grid-child">
			<div>
				<h1 class="page-header"><?php echo Text::_('JERROR_LAYOUT_PAGE_NOT_FOUND'); ?></h1>
				<jdoc:include type="message" />
				<p><strong><?php echo Text::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></strong></p>
				<p><?php echo Text::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></p>
				<ul>
					<li><?php echo Text::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
					<li><?php echo Text::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
					<li><?php echo Text::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
					<li><?php echo Text::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
				</ul>
				<p><?php echo Text::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?></p>
				<p><a href="<?php echo $this->baseurl; ?>/index.php" class="btn btn-secondary"><span class="icon-home" aria-hidden="true"></span> <?php echo Text::_('JERROR_LAYOUT_HOME_PAGE'); ?></a></p>
				<hr>
				<p><?php echo Text::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?></p>
				<blockquote>
					<span class="badge bg-secondary"><?php echo $this->error->getCode(); ?></span> <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
				</blockquote>
				<?php 
				if ( $this->debug OR $is_user_super ){ 
				?>
					<div>
						<?php echo $this->renderBacktrace(); ?>
						<?php // Check if there are more Exceptions and render their data as well ?>
						<?php if ($this->error->getPrevious()) : ?>
							<?php $loop = true; ?>
							<?php // Reference $this->_error here and in the loop as setError() assigns errors to this property and we need this for the backtrace to work correctly ?>
							<?php // Make the first assignment to setError() outside the loop so the loop does not skip Exceptions ?>
							<?php $this->setError($this->_error->getPrevious()); ?>
							<?php while ($loop === true) : ?>
								<p><strong><?php echo Text::_('JERROR_LAYOUT_PREVIOUS_ERROR'); ?></strong></p>
								<p><?php echo htmlspecialchars($this->_error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></p>
								<?php echo $this->renderBacktrace(); ?>
								<?php $loop = $this->setError($this->_error->getPrevious()); ?>
							<?php endwhile; ?>
							<?php // Reset the main error object to the base error ?>
							<?php $this->setError($this->error); ?>
						<?php endif; ?>
					</div>
				<?php 
				}
				?>		
			</div>
		</div>
	</main>

	<?php if ( $this->countModules('footer_left', true) || $this->countModules('footer_right', true) || $this->countModules('footer_middle', true) ) : ?>
	<footer class="container-footer footer full-width fixed-bottom">
		<div class="row">
			<div class="pull-left footer_left">
				<jdoc:include type="modules" name="footer_left" style="none" />
			</div>
			<div class="footer_middle">
				<jdoc:include type="modules" name="footer_middle" style="none" />
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
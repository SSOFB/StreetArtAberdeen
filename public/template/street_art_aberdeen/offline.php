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
use Joomla\CMS\Helper\AuthenticationHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$twofactormethods = AuthenticationHelper::getTwoFactorMethods();
$extraButtons     = AuthenticationHelper::getLoginButtons('form-login');
$app              = Factory::getApplication();
$wa               = $this->getWebAssetManager();

$fullWidth = 1;

// Template path
$templatePath = 'templates/' . $this->template;

// Color Theme
$paramsColorName = $this->params->get('colorName', 'colors_standard');
$assetColorName  = 'theme.' . $paramsColorName;
$wa->registerAndUseStyle($assetColorName, $templatePath . '/css/global/' . $paramsColorName . '.css');

// Use a font scheme if set in the template style options
$paramsFontScheme = $this->params->get('useFontScheme', false);
$fontStyles       = '';

if ($paramsFontScheme)
{
	if (stripos($paramsFontScheme, 'https://') === 0)
	{
		$this->getPreloadManager()->preconnect('https://fonts.googleapis.com/', []);
		$this->getPreloadManager()->preconnect('https://fonts.gstatic.com/', []);
		$this->getPreloadManager()->preload($paramsFontScheme, ['as' => 'style']);
		$wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, [], ['media' => 'print', 'rel' => 'lazy-stylesheet', 'onload' => 'this.media=\'all\'']);

		if (preg_match_all('/family=([^?:]*):/i', $paramsFontScheme, $matches) > 0)
		{
			$fontStyles = '--street_art_aberdeen-font-family-body: "' . str_replace('+', ' ', $matches[1][0]) . '", sans-serif;
			--street_art_aberdeen-font-family-headings: "' . str_replace('+', ' ', isset($matches[1][1]) ? $matches[1][1] : $matches[1][0]) . '", sans-serif;
			--street_art_aberdeen-font-weight-normal: 400;
			--street_art_aberdeen-font-weight-headings: 700;';
		}
	}
	else
	{
		$wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, ['version' => 'auto'], ['media' => 'print', 'rel' => 'lazy-stylesheet', 'onload' => 'this.media=\'all\'']);
		$this->getPreloadManager()->preload($wa->getAsset('style', 'fontscheme.current')->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);
	}
}

// Enable assets
$wa->usePreset('template.street_art_aberdeen.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
	->useStyle('template.active.language')
	->useStyle('template.offline')
	->useStyle('template.user')
	->useScript('template.user')
	->addInlineStyle(":root {
		--hue: 214;
		--template-bg-light: #f0f4fb;
		--template-text-dark: #495057;
		--template-text-light: #ffffff;
		--template-link-color: #2a69b8;
		--template-special-color: #001B4C;
		$fontStyles
	}");

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.street_art_aberdeen.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

// Logo file or site title param
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');

// Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon-pinned.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);

if ($this->params->get('logoFile'))
{
	$logo = '<img src="' . htmlspecialchars(Uri::root() . $this->params->get('logoFile'), ENT_QUOTES, 'UTF-8') . '" alt="' . $sitename . '">';
}
elseif ($this->params->get('siteTitle'))
{
	$logo = '<span title="' . $sitename . '">' . htmlspecialchars($this->params->get('siteTitle'), ENT_COMPAT, 'UTF-8') . '</span>';
}
else
{
	$logo = '<img src="' . $templatePath . '/images/logo.svg" class="logo d-inline-block" alt="' . $sitename . '">';
}

// Defer font awesome
$wa->getAsset('style', 'fontawesome')->setAttribute('rel', 'lazy-stylesheet');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
</head>
<body class="site offline">
	<div class="outer">
		<div class="offline-card">
			<div class="header">
				<h1><?php echo $sitename; ?></h1>
			<?php if ($app->get('display_offline_message', 1) == 1 && str_replace(' ', '', $app->get('offline_message')) != '') : ?>
				<p><?php echo $app->get('offline_message'); ?></p>
			<?php elseif ($app->get('display_offline_message', 1) == 2) : ?>
				<p><?php echo Text::_('JOFFLINE_MESSAGE'); ?></p>
			<?php endif; ?>
			</div>
			<div class="login">
				<jdoc:include type="message" />
				<form action="<?php echo Route::_('index.php', true); ?>" method="post" id="form-login">
					<fieldset>
						<label for="username"><?php echo Text::_('JGLOBAL_USERNAME'); ?></label>
						<input name="username" class="form-control" id="username" type="text">

						<label for="password"><?php echo Text::_('JGLOBAL_PASSWORD'); ?></label>
						<input name="password" class="form-control" id="password" type="password">

						<?php if (count($twofactormethods) > 1) : ?>
						<label for="secretkey"><?php echo Text::_('JGLOBAL_SECRETKEY'); ?></label>
						<input name="secretkey" autocomplete="one-time-code" class="form-control" id="secretkey" type="text">
						<?php endif; ?>

						<?php foreach($extraButtons as $button):
							$dataAttributeKeys = array_filter(array_keys($button), function ($key) {
								return substr($key, 0, 5) == 'data-';
							});
							?>
							<div class="mod-login__submit form-group">
								<button type="button"
										class="btn btn-secondary w-100 mt-4 <?php echo $button['class'] ?? '' ?>"
								<?php foreach ($dataAttributeKeys as $key): ?>
									<?php echo $key ?>="<?php echo $button[$key] ?>"
								<?php endforeach; ?>
								<?php if ($button['onclick']): ?>
									onclick="<?php echo $button['onclick'] ?>"
								<?php endif; ?>
								title="<?php echo Text::_($button['label']) ?>"
								id="<?php echo $button['id'] ?>"
								>
								<?php if (!empty($button['icon'])): ?>
									<span class="<?php echo $button['icon'] ?>"></span>
								<?php elseif (!empty($button['image'])): ?>
									<?php echo $button['image']; ?>
								<?php elseif (!empty($button['svg'])): ?>
									<?php echo $button['svg']; ?>
								<?php endif; ?>
								<?php echo Text::_($button['label']) ?>
								</button>
							</div>
						<?php endforeach; ?>

						<input type="submit" name="Submit" class="btn btn-primary" value="<?php echo Text::_('JLOGIN'); ?>">

						<input type="hidden" name="option" value="com_users">
						<input type="hidden" name="task" value="user.login">
						<input type="hidden" name="return" value="<?php echo base64_encode(Uri::base()); ?>">
						<?php echo HTMLHelper::_('form.token'); ?>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</body>
</html>

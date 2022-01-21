<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_footer
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

#use Joomla\CMS\Language\Text;

?>
<div class="mod-footer">
	<div class="footer1"><?php echo $app->getCfg( 'sitename' ) . " - " . date('l jS \of F Y, g:i a') ?></div>
</div>

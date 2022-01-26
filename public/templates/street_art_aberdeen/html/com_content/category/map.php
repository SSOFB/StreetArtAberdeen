<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;

?>
<div class="saa_map_screen">

<?php
$this->subtemplatename = 'articles';
echo LayoutHelper::render('joomla.content.category_default', $this);
?>

</div>

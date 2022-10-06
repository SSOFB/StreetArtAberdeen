<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.savedebug
 *
 * @copyright   (C) 2022 SSOFB
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\String\StringHelper;

/**
 * Email cloak plugin class.
 *
 * @since  1.5
 */
class PlgContentSavedebug extends CMSPlugin
{
	/**
	 * The Application object
	 *
	 * @var    JApplicationSite
	 * @since  3.9.0
	 */
	protected $app;


	/**
	 * Content is passed by reference. Method is called before the content is saved.
     * https://docs.joomla.org/Plugin/Events/Content#onContentBeforeSave
	 *
	 * @param   string  $context  The context of the content passed to the plugin (added in 1.6).
	 * @param   object  $article  A JTableContent object.
	 * @param   bool    $isNew    If the content is just about to be created.
     * @param   array   data    The data to save. 
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	public function onContentBeforeSave($context, &$article, $isNew, &$data=Array()) {
        $this->ilog("onContentBeforeSave");
		$this->ilog("article id: " . $article->id);
		$this->ilog("context: " . $context);
		$this->ilog("POST: " . print_r($_POST, true));
		$this->ilog("GET: " . print_r($_GET, true));
		$this->ilog("FILES: " . print_r($_FILES, true));
		$this->ilog("Headers: " . print_r(getallheaders(), true));
    }


	/**
	 * Content is passed by reference. Method is called after the content is saved.
     * https://docs.joomla.org/Plugin/Events/Content#onContentAfterSave
	 *
	 * @param   string  $context  The context of the content passed to the plugin (added in 1.6).
	 * @param   object  $article  A JTableContent object.
	 * @param   bool    $isNew    If the content is just about to be created.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
    public function onContentAfterSave($context, &$article, $isNew) {
		$this->ilog("onContentAfterSave");
		$this->ilog("article id: " . $article->id);
		$this->ilog("context: " . $context);
		$this->ilog("POST: " . print_r($_POST, true));
		$this->ilog("GET: " . print_r($_GET, true));
		$this->ilog("FILES: " . print_r($_FILES, true));
		$this->ilog("Headers: " . print_r(getallheaders(), true));
	}


    /**
    * very simple logging function
    *
    * @param   string    the log string
    * @param   int       log level
    */    
    function ilog($log_string, $level=1) {
        $logging_level = 0;
        
        if ( $level > $logging_level ) {
            $log_file = JPATH_ADMINISTRATOR . '/logs/savedebug_plugin.log';
            $fh = fopen($log_file, 'a') or die();
            $log_string = date("Y-m-d H:i:s") . " : " . $log_string . "\n";
            fwrite($fh, $log_string);
            fclose($fh);  
        }
    }

}

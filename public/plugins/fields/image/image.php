<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Image
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

#JLoader::import('components.com_fields.libraries.fieldsfileplugin', JPATH_ADMINISTRATOR);
use Joomla\CMS\Form\Form;
use Joomla\CMS\Factory;

/**
 * Fields Text Plugin
 *
 * @since  3.7.0
 */
class PlgFieldsImage extends \Joomla\Component\Fields\Administrator\Plugin\FieldsPlugin {


	/**
	 * Transforms the field into a DOM XML element and appends it as a child on the given parent.
	 *
	 * @param   stdClass    $field   The field.
	 * @param   DOMElement  $parent  The field node parent.
	 * @param   Form       $form    The form.
	 *
	 * @return  DOMElement
	 *
	 * @since   3.7.0
	 */
	public function onCustomFieldsPrepareDom($field, DOMElement $parent, Form $form)
	{

        # TODO: make the form be `enctype="multipart/form-data"`
        
        #$field->type = "file";


        
        #echo "<hr/>";
        #echo "<pre>field: " . print_r($field, TRUE) . "</pre>";
        #echo "<pre>parent: " . print_r($parent, TRUE) . "</pre>";
        #echo "<pre>form: " . print_r($form, TRUE) . "</pre>";
        
        $this->ilog("onCustomFieldsPrepareDom");
        $this->ilog("field: " . print_r($field, TRUE));
        $this->ilog("parent: " . print_r($parent, TRUE));
        $this->ilog("form: " . print_r($form, TRUE));        

        $fieldNode = parent::onCustomFieldsPrepareDom($field, $parent, $form);
        $this->ilog("fieldNode: " . print_r($fieldNode, TRUE));

		if (!$fieldNode ){
			
            return $fieldNode;
        } else {

            $fieldNode->setAttribute('field_id', $field->id);
            $fieldNode->setAttribute('waffle', "tattie");

            if ($field->id == 6) {
                #echo "<pre>field: " . print_r($field, TRUE) . "</pre>";
                #echo "<pre>fieldNode: " . print_r($fieldNode, TRUE) . "</pre>";
                #echo "<pre>fieldNode parentNode: " . print_r($fieldNode->parentNode, TRUE) . "</pre>";
                #echo "<pre>fieldNode parentNode attributes: " . print_r($fieldNode->parentNode->attributes, TRUE) . "</pre>";
                #echo "<pre>fieldNode parentNode parentNode: " . print_r($fieldNode->parentNode->parentNode->parentNode, TRUE) . "</pre>";

                #echo "<pre>fieldNode parentNode parentNode: " . print_r($fieldNode->parentNode->parentNode->parentNode->attributes, TRUE) . "</pre>";

                #echo "<pre>fieldNode childNodes: " . print_r($fieldNode->childNodes, TRUE) . "</pre>";
                #echo "<pre>fieldNode previousSibling: " . print_r($fieldNode->previousSibling, TRUE) . "</pre>";
                #echo "<pre>fieldNode attributes: " . print_r($fieldNode->attributes, TRUE) . "</pre>";
            }
            return $fieldNode;
        }  
	}  
    

	/**
	 * Content is passed by reference. Method is called before the content is saved.
	 *
	 * @param   string  $context  The context of the content passed to the plugin (added in 1.6).
	 * @param   object  $article  A JTableContent object.
	 * @param   bool    $isNew    If the content is just about to be created.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */

	public function onContentBeforeSave($context, $article, $isNew)
	{
		#echo "<pre>context: " . $context . "</pre>";
        #echo "<pre>article id: " . $article->id . "</pre>";
        #echo "<pre>article: " . print_r($article, TRUE) . "</pre>";

        $this->ilog("onContentBeforeSave");
        $this->ilog("context: " . $context);
        #$this->ilog("article: " . print_r($article, TRUE));
        $this->ilog("isNew: " . $isNew);

        $this->ilog("POST: " . print_r($_POST, true));
        $this->ilog("GET: " . print_r($_GET, true));
        $headers_array = array_change_key_case(getallheaders(), CASE_LOWER);
        $this->ilog("Headers: " . print_r($headers_array, true), 6);

        JFactory::getApplication()->enqueueMessage("onContentBeforeSave");
        JFactory::getApplication()->enqueueMessage("article id: " . $article->id);


        $input = Factory::getApplication()->input;
        $files = $input->files->get('jform');
        $this->ilog("files: " . print_r($files, true), 6);

        # TODO: set image filename
        # TODO: move image into dir
        # TODO: set filename as field value

        JFactory::getApplication()->enqueueMessage("files: " . print_r($files, TRUE));   

	}



	/**
	 * onAfterDispatch
     * https://docs.joomla.org/Plugin/Events/System#onAfterDispatch
     * 
     * https://joomla.stackexchange.com/questions/31787/
	 *
	 */
    public function onAfterDispatch()
    {
        # Get the document.
        $doc = $this->app->getDocument();
    
        # Check that we are manipulating a HTML document.
        if (!($doc instanceof Joomla\CMS\Document\HtmlDocument)) {
            return;
        }

        # Check that we're in correct application.
        if (!$this->app->isClient('site') && !$this->app->isClient('administrator')) {
            return;
        }

        # Check the component.
        $input = $this->app->getInput();
        if ($input->get('option') !== 'com_content') {
            return;
        }

        # Check the views.
        if ($this->app->isClient('site') && $input->get('view') !== 'form') {
            return;
        }

        if ($this->app->isClient('administrator') && $input->get('view') !== 'article') {
            return;
        }


        // Get the HTML content.
        $html = $doc->getBuffer('component');
    
        // Add the attribute.
        $html = str_replace("method=\"post\" name=\"adminForm\" id=\"adminForm\" ", "method=\"post\" enctype=\"multipart/form-data\" name=\"adminForm\" id=\"adminForm\" ", $html);
    
        // Set the updated HTML.
        $doc->setBuffer($html, 'component');
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
            $log_file = JPATH_BASE . '/administrator/logs/field_image_plugin.log';
            $fh = fopen($log_file, 'a') or die();
            $log_string = date("Y-m-d H:i:s") . " : " . $log_string . "\n";
            fwrite($fh, $log_string);
            fclose($fh);  
        }
    }  	
    
    

}

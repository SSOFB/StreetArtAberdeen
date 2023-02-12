<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Image
 *
 * @copyright   (C) 2022 SSOFB Ltd
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

#JLoader::import('components.com_fields.libraries.fieldsfileplugin', JPATH_ADMINISTRATOR);
use Joomla\CMS\Form\Form;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;

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
        #$this->ilog("field: " . print_r($field, TRUE));
        #$this->ilog("parent: " . print_r($parent, TRUE));
        #$this->ilog("form: " . print_r($form, TRUE));        

        $fieldNode = parent::onCustomFieldsPrepareDom($field, $parent, $form);
        #$this->ilog("fieldNode: " . print_r($fieldNode, TRUE));

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
        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_fields/models');
        $model_field = JModelLegacy::getInstance('Field', 'FieldsModel', ['ignore_request' => true]);
        #$this->ilog("model_field: " . print_r($model_field, true));

        $field_value = $model_field->getFieldValue(6, $article->id);
        $this->ilog("field_value: " . $field_value); 
    }



    public function onContentAfterSave($context, &$article, $isNew) {
        $this->ilog("onContentAfterSave");
        $this->ilog("article id: " . $article->id);
        $this->ilog("POST: " . print_r($_POST, true));
        $this->ilog("GET: " . print_r($_GET, true));
        $this->ilog("FILES: " . print_r($_FILES, true));
        $this->ilog("Headers: " . print_r(getallheaders(), true));

        $input = Factory::getApplication()->input;
        $files = $input->files->get('jform');
        $this->ilog("files: " . print_r($files, true));

        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_fields/models');
        $model_field = JModelLegacy::getInstance('Field', 'FieldsModel', ['ignore_request' => true]);
        #$this->ilog("model_field: " . print_r($model_field, true));

        $field_value = $model_field->getFieldValue(6, $article->id);
        $this->ilog("field_value: " . $field_value); 

        jimport('joomla.filesystem.file');

        foreach ( $files['com_fields'] AS $field_name=>$field_data ) {
            $this->ilog("field_name: " . $field_name);
            # check there were no errors
            if ( $field_data['error'] == 0 ) {
                $this->ilog("no errors, field_data: " . print_r($field_data, true));
    
                # set image filename
                list($file_type, $file_extension) = explode("/", $field_data['type']);

                # check it's an image
                if ( $file_type == "image" ) {
                    $file_url = "images/orig_images/image-field-file_id" . $article->id . "_"  . date("Y-m-d_H-i-s") . "_" . rand(1000, 9999) . "." . $file_extension;
                    $file_name = JPATH_SITE . "/" . $file_url;
                    $this->ilog("file_url: " . $file_url);
                    $this->ilog("file_name: " . $file_name);
    
                    # move image into dir
                    File::upload($field_data['tmp_name'], $file_name);
                 
                    $field_id = $this->get_field_id_from_name($field_name);
        
                    #set the value using field model instead to make change permanent in db
                    $model_field->setFieldValue($field_id, $article->id, $file_url);
                }
            } else {
                $this->ilog("field error: " .$field_data['error']);


                # name="jform[com_fields][photo_hidden]"
                $jform = $input->post->get("jform");
                $this->ilog("jform: " . print_r($jform, true));

                #$hidden_field_name = "jform['com_fields']['" .$field_name . "_hidden']";
                #$this->ilog("hidden_field_name: " . $hidden_field_name);
                #$value_to_keep = $input->post->get($hidden_field_name);

                $hidden_field_name =  $field_name . "_hidden";

                $value_to_keep = $jform['com_fields'][$hidden_field_name];
                $this->ilog("value_to_keep: " . $value_to_keep);

                # TODO: figure out why the slash is getting removed
                $value_to_keep = str_replace("imagesorig_images", "images/orig_images/", $value_to_keep);
                $this->ilog("value_to_keep, fixed: " . $value_to_keep);

                $field_id = $this->get_field_id_from_name($field_name);
    
                #set the value using field model instead to make change permanent in db
                $model_field->setFieldValue($field_id, $article->id, $value_to_keep);

            }
        }
    }


	/**
	 * onAfterDispatch
     * We use this to make the com_content edit form use enctype="multipart/form-data" in the form tag
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

        # Get the HTML content.
        $html = $doc->getBuffer('component');
    
        # Add the attribute.
        $html = str_replace("method=\"post\" name=\"adminForm\" id=\"adminForm\" ", "method=\"post\" enctype=\"multipart/form-data\" name=\"adminForm\" id=\"adminForm\" ", $html);
    
        # Set the updated HTML.
        $doc->setBuffer($html, 'component');
    }

    /**
    * get the field ID from the name
    *
    * @param   string    the log string
    * @param   int       log level
    */    
    function get_field_id_from_name($field_name) {
        $db = JFactory::getDbo();
        $query = $db
            ->getQuery(true)
            ->select('id')
            ->from($db->quoteName('#__fields'))
            ->where($db->quoteName('name') . " = " . $db->quote($field_name));
        $db->setQuery($query);
        $field_id = $db->loadResult();
        $this->ilog("field_id: " . $field_id); 
        return $field_id;
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
            $log_file = JPATH_ADMINISTRATOR . '/logs/field_image_plugin.log';
            $fh = fopen($log_file, 'a') or die();
            $log_string = date("Y-m-d H:i:s") . " : " . $log_string . "\n";
            fwrite($fh, $log_string);
            fclose($fh);  
        }
    }
    
    

}

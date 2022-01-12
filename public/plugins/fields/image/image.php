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

	public function onContentBeforeSave($context, &$article, $isNew, &$data=Array())
	{
		#echo "<pre>context: " . $context . "</pre>";
        #echo "<pre>article id: " . $article->id . "</pre>";
        #echo "<pre>article: " . print_r($article, TRUE) . "</pre>";

        /*
        $this->ilog("onContentBeforeSave");
        $this->ilog("context: " . $context);
        #$this->ilog("article: " . print_r($article, TRUE));
        $this->ilog("data: " . print_r($data, TRUE));
        $this->ilog("isNew: " . $isNew);

        $this->ilog("POST: " . print_r($_POST, true));
        $this->ilog("GET: " . print_r($_GET, true));
        $headers_array = array_change_key_case(getallheaders(), CASE_LOWER);
        $this->ilog("Headers: " . print_r($headers_array, true), 6);

        $attribs = json_decode($article->attribs);
        $this->ilog("attribs: " . print_r($attribs, true));

        #$fields = json_decode($article->fields);
        #$this->ilog("fields: " . print_r($fields, true));

        JFactory::getApplication()->enqueueMessage("onContentBeforeSave");
        JFactory::getApplication()->enqueueMessage("article id: " . $article->id);


        $input = Factory::getApplication()->input;
        $files = $input->files->get('jform');
        $this->ilog("files: " . print_r($files, true), 6);



        jimport('joomla.filesystem.file');

        foreach ( $files['com_fields'] AS $field_name=>$field_data ) {
            # set image filename
            list($file_type, $file_extention) = explode("/", $field_data['type']);
            $filename = JPATH_SITE . "/images/image_field_file_" . date("Y-m-d_H-i-s") . "_" . rand(1000, 9999) . "." . $file_extention;
            
            # move image into dir
            File::upload($field_data['tmp_name'], $filename);
            
            
            # set filename as field value
            $jform = $input->get('jform', array(), 'array');
            $jform['com_fields'][$field_name] = $filename;
            $jform['articletext'] = date("Y-m-d_H-i-s");
            #$input->set("jform['articletext']", date("Y-m-d_H-i-s") );
            $input->set("jform", $jform);
            #$input->setValue($field_name, "com_fields", $filename);


            #$data = JRequest::getVar( 'jform', null, 'post', 'array' );
            #$data['com_fields'][$field_name] = strtolower( $filename );
            #JRequest::setVar('jform', $data );

            $this->ilog("input: " . print_r($input, true));

            $data['com_fields'][$field_name] = $filename;
            #$data['articletext'] = date("Y-m-d_H-i-s");
        }

        $this->ilog("data: " . print_r($data, TRUE));

        JFactory::getApplication()->enqueueMessage("files: " . print_r($files, TRUE));   

        $article->introtext = "coded introtext";
        $article->fulltext = "coded fulltext";
        #$article->fields["medium"] = "coded medium";
        $data['com_fields']["medium"] = "3D";

        return true;

        */

	}



    public function onContentAfterSave($context, &$article, $isNew) {
        $this->ilog("onContentAfterSave");
        $this->ilog("article id: " . $article->id);


        $this->ilog("POST: " . print_r($_POST, true));
        $this->ilog("GET: " . print_r($_GET, true));
        $this->ilog("FILES: " . print_r($_FILES, true));
        $headers_array = array_change_key_case(getallheaders(), CASE_LOWER);
        $this->ilog("Headers: " . print_r($headers_array, true), 6);


        $input = Factory::getApplication()->input;
        $files = $input->files->get('jform');
        $this->ilog("files: " . print_r($files, true), 6);


        
        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_fields/models');
        $model_field = JModelLegacy::getInstance('Field', 'FieldsModel', ['ignore_request' => true]);
        #$this->ilog("model_field: " . print_r($model_field, true));

        jimport('joomla.filesystem.file');

        foreach ( $files['com_fields'] AS $field_name=>$field_data ) {

            if ( $field_data['error'] == 0 ) {
                $this->ilog("field_name: " . $field_name);
                $this->ilog("field_data: " . print_r($field_data, true));
    
                # set image filename
                list($file_type, $file_extension) = explode("/", $field_data['type']);
                $file_url = "images/image-field-file_id" . $article->id . "_"  . date("Y-m-d_H-i-s") . "_" . rand(1000, 9999) . "." . $file_extension;
                $file_name = JPATH_SITE . "/" . $file_url;
                $this->ilog("file_url: " . $file_url);
                $this->ilog("file_name: " . $file_name);
                


                # move image into dir
                File::upload($field_data['tmp_name'], $file_name);
                
                
                # set filename as field value
                #$jform = $input->get('jform', array(), 'array');
                #$jform['com_fields'][$field_name] = $filename;
                #$jform['articletext'] = date("Y-m-d_H-i-s");
                #$input->set("jform", $jform);
    
                #$this->ilog("input: " . print_r($input, true));
    
                #$data['com_fields'][$field_name] = $filename;
                #$data['articletext'] = date("Y-m-d_H-i-s");
    
                $db = JFactory::getDbo();
                $query = $db
                    ->getQuery(true)
                    ->select('id')
                    ->from($db->quoteName('#__fields'))
                    ->where($db->quoteName('name') . " = " . $db->quote($field_name));
                $db->setQuery($query);
                $field_id = $db->loadResult();
                $this->ilog("field_id: " . $field_id);
    
    
                //set the value using field model instead to make change permanent in db
                $model_field->setFieldValue($field_id, $article->id, $file_url);
            }



        }






    
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
            $log_file = JPATH_ADMINISTRATOR . '/logs/field_image_plugin.log';
            $fh = fopen($log_file, 'a') or die();
            $log_string = date("Y-m-d H:i:s") . " : " . $log_string . "\n";
            fwrite($fh, $log_string);
            fclose($fh);  
        }
    }  	
    
    

}

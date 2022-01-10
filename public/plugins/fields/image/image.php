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

        #$field->type = "file";
        
        #echo "<hr/>";
        #echo "<pre>field: " . print_r($field, TRUE) . "</pre>";
        #echo "<pre>parent: " . print_r($parent, TRUE) . "</pre>";
        #echo "<pre>form: " . print_r($form, TRUE) . "</pre>";
        
        

        $fieldNode = parent::onCustomFieldsPrepareDom($field, $parent, $form);

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
	 * Content is passed by reference. Method is called after the content is saved.
	 *
	 * @param   string  $context  The context of the content passed to the plugin (added in 1.6).
	 * @param   object  $article  A JTableContent object.
	 * @param   bool    $isNew    If the content is just about to be created.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	public function onContentAfterSave($context, $article, $isNew)
	{
		#echo "<pre>context: " . $context . "</pre>";
        #echo "<pre>article id: " . $article->id . "</pre>";
        #echo "<pre>article: " . print_r($article, TRUE) . "</pre>";


        JFactory::getApplication()->enqueueMessage("onContentAfterSave");
        JFactory::getApplication()->enqueueMessage("article id: " . $article->id);


        #$input = Factory::getApplication()->input;
        #$form = $input->get('jform');

        JFactory::getApplication()->enqueueMessage("form: " . print_r($form, TRUE));   

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


        JFactory::getApplication()->enqueueMessage("onContentBeforeSave");
        JFactory::getApplication()->enqueueMessage("article id: " . $article->id);


        $input = Factory::getApplication()->input;
        $files = $input->files->get('jform');

        JFactory::getApplication()->enqueueMessage("files: " . print_r($files, TRUE));   

	}


	/**
	 * Prepare form and add my field.
	 *
	 * @param   JForm  $form  The form to be altered.
	 * @param   mixed  $data  The associated data for the form.
	 *
	 * @return  boolean
	 *
	 * @since   <your version>
	 */
	function onContentPrepareForm($form, $data)
	{

        JFactory::getApplication()->enqueueMessage("onContentPrepareForm");
		return true;
	}

    /**
    * Injects Insert Tags input box and drop down menu to adminForm
    *
    * @access   public
    * @since    1.5
    */
    #function onAfterRender()
    #{
    #    JFactory::getApplication()->enqueueMessage("onContentPrepareForm");
    #}

	/**
	 * Plugin that cloaks all emails in content from spambots via Javascript.
	 *
	 * @param   string   $context  The context of the content being passed to the plugin.
	 * @param   mixed    &$row     An object with a "text" property or the string to be cloaked.
	 * @param   mixed    &$params  Additional parameters. See {@see PlgContentEmailcloak()}.
	 * @param   integer  $page     Optional page number. Unused. Defaults to zero.
	 *
	 * @return  boolean	True on success.
	 */
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		JFactory::getApplication()->enqueueMessage("onContentPrepare");
        return true;
	}

}

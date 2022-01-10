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
                echo "<pre>field: " . print_r($field, TRUE) . "</pre>";
                echo "<pre>fieldNode: " . print_r($fieldNode, TRUE) . "</pre>";
                #echo "<pre>fieldNode parentNode: " . print_r($fieldNode->parentNode, TRUE) . "</pre>";
                #echo "<pre>fieldNode childNodes: " . print_r($fieldNode->childNodes, TRUE) . "</pre>";
                #echo "<pre>fieldNode previousSibling: " . print_r($fieldNode->previousSibling, TRUE) . "</pre>";
                #echo "<pre>fieldNode attributes: " . print_r($fieldNode->attributes, TRUE) . "</pre>";
            }
    
    
            
    
            return $fieldNode;
        }       
        

	}    
}

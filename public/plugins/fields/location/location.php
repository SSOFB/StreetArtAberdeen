<?php

/**
 * @copyright   Copyright (C) 2005 - 2017 Michael Richey. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::import('components.com_fields.libraries.fieldsplugin', JPATH_ADMINISTRATOR);
require_once __DIR__.'/helper.php';
/**
 * Fields Location Plugin
 *
 * @since  3.7.0
 */
class PlgFieldsLocation extends FieldsPlugin
{
	public $apikey = false;
        public $scriptAdded = false;
        
	/**
	 * Transforms the field into a DOM XML element and appends it as a child on the given parent.
	 *
	 * @param   stdClass    $field   The field.
	 * @param   DOMElement  $parent  The field node parent.
	 * @param   JForm       $form    The form.
	 *
	 * @return  DOMElement
	 *
	 * @since   3.7.0
	 */
	public function onCustomFieldsPrepareDom($field, DOMElement $parent, JForm $form)
	{
		$fieldNode = parent::onCustomFieldsPrepareDom($field, $parent, $form);

		if (!$fieldNode)
		{
			return $fieldNode;
		}
		$fieldNode->setAttribute('readonly', ($field->fieldparams->get('llreadonly',true)?true:false));
		$debug = JFactory::getConfig()->get('debug',false);
		$doc = JFactory::getDocument();
		JHtml::_('jquery.framework',true);
                $this->apikey = $this->params->get('apikey', false);
                error_log($this->apikey);
		if ($this->apikey)
		{
			$latlon = (false===strpos($field->value,','))?array(0,0):explode(',', $field->value);
			$fieldid = 'plg_fields_location_' . $field->name . '_' . $field->id;
			$targetid = 'jform_com_fields_' . $field->name;
                        $urlvars = array();
                        if($this->params->get('searchbox',false)) {
                            $urlvars[] = 'libraries=places';
                            JText::script('PLG_FIELDS_LOCATION_SEARCHBOX_PLACEHOLDER');
                        }
                        plgFieldsLocationHelper::loadMapsAPI($this->params, $debug);
			$options = array(
				'zoom'=>$field->fieldparams->get('editzoom', 1),
				'center'=>array($latlon[0]?:0, $latlon[1]?:0),
				'mapTypeId'=>$field->fieldparams->get('maptype', 'ROADMAP'),
                                'searchbox'=>($this->params->get('searchbox',0) && $field->fieldparams->get('searchbox',0))?1:0
			);
			$doc->addScriptOptions('plg_fields_location_'.$field->id,$options);
			$width = $field->fieldparams->get('editwidth', '400px');
			$height = $field->fieldparams->get('editheight', '300px');
			$doc->addStyleDeclaration('#' . $fieldid . ' { width: ' . ($width == 'auto' ? '100%' : $width) . '; height: ' . ($height == 'auto' ? '100%' : $height) . '; margin-bottom: 20px; }');
			$doc->addScriptDeclaration('jQuery(document).ready(function($){window.plg_fields_location.registerField('.$field->id.',"'.$fieldid.'","'.$targetid.'");});');
		}
		
		return $fieldNode;
	}

}

<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Image
 *
 * @copyright   Copyright (C) 2017 NAME. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('file');

class JFormFieldImage extends JFormFieldFile
{
	public $type = 'Image';



	# <input type="file" name="jform[com_fields][image-field-test-01]" id="jform_com_fields_image_field_test_01" class="form-control">

    /**
     * Method to get a list of options for a list input.
     *
     * @return  array  An array of JHtml options.
     */
	/*
    public function getInput() {
        return '<div class="filename_labe">'.$this->value.'</div><input name="'.$this->name.'" id="'.$this->id.'" accept="image/*" aria-invalid="false" type="file" value="'.$this->value.'">';
        // code that returns HTML that will be shown as the form field
    }
	*/

	/*
	public function setInput(){
		echo "ok";
	}
	*/
}

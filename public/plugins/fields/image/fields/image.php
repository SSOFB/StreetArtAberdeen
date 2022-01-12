<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Image
 *
 * @copyright   (C) 2022 SSOFB Ltd
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('file');

class JFormFieldImage extends JFormFieldFile
{
	public $type = 'Image';

    /**
     * Method to get a list of options for a list input.
     *
     * @return  array  An array of JHtml options.
     */
    public function getInput() {
        $html = "";
        #$html .= "<pre>" . print_r($this, TRUE) . "</pre>";

        if ( strlen($this->value) ) {
            $html .= "<img class=\"image_field_display\" alt=\"" . $this->label . "\" src=\"" . $this->value . "\" />";
            $html .= "<p>Replace this image</p>";
        } else {
            $html .= "<p>Choose an image</p>";
        }

        $html .= "<input name=\"" . $this->name . "\" id=\"" . $this->id . "\" accept=\"image/*\" aria-invalid=\"false\" type=\"file\" value=\"" . $this->value . "\">";

        return $html;
    }

}

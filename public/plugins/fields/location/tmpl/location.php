<?php

/**
 * @copyright   Copyright (C) 2005 - 2017 Michael Richey. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
require_once dirname(__DIR__) . '/helper.php';
$app = JFactory::getApplication();
$value = strlen(trim($field->value)) ? trim($field->value) : $field->fieldparams->get('default_value', '0,0');

if ($value == '') {
    return;
}

if (is_array($value)) {
    $value = implode(', ', $value);
}
$latlon = explode(',', $value);
$displaytype = $this->params->get('staticapikey', false) ? $field->fieldparams->get('displaytype', 'map') : 'map';
if ($app->input->getCmd('layout', false) !== 'edit') {
    if (!$this->params->get('apikey', false) || $displaytype == 'text') {
        echo JText::_('PLG_FIELDS_LOCATION_DISPLAY_LATITUDE') . $latlon[0];
        echo '<br />';
        echo JText::_('PLG_FIELDS_LOCATION_DISPLAY_LONGITUDE') . $latlon[1];
    } else {
        $zoom = $field->fieldparams->get('displayzoom', 1);
        $width = $field->fieldparams->get('displaywidth', 'auto');
        $height = $field->fieldparams->get('displayheight', '200px');
        $fieldid = 'plg_fields_location_' . $field->name . '_' . $field->id;
        switch ($displaytype) {
            case 'map':
                $maptype = $field->fieldparams->get('maptype', 'ROADMAP');
                $fieldid = 'plg_fields_location_' . $field->name . '_' . $field->id;
                $debug = JFactory::getConfig()->get('debug', false);

                $doc = JFactory::getDocument();
                plgFieldsLocationHelper::loadMapsAPI($this->params, $debug);
                $doc->addScript(JURI::root(true) . '/media/plg_fields_location/plg_fields_location' . ($debug ? '' : '.min') . '.js', array('version' => 'auto'));
                $doc->addStyleDeclaration('#' . $fieldid . ' { width: ' . ($width == 'auto' ? '100%' : $width) . '; height: ' . ($height == 'auto' ? '100%' : $height) . '; margin-bottom: 20px; }');
                $options = array(
                    'zoom' => $field->fieldparams->get('displayzoom', 1),
                    'center' => array($latlon[0] ?: 0, $latlon[1] ?: 0),
                    'mapTypeId' => $field->fieldparams->get('maptype', 'ROADMAP'),
                    'searchbox' => false
                );
                $doc->addScriptOptions('plg_fields_location_' . $field->id, $options);
                $doc->addScriptDeclaration('jQuery(document).ready(function($){window.plg_fields_location.registerDisplay(' . $field->id . ',"' . $fieldid . '");});');
                echo '<div id="' . $fieldid . '"></div>';
                break;
            case 'static':
                $mapurl = plgFieldsLocationHelper::createStaticMapURL($this->params, $value, $zoom, $width, $height);
                echo '<img src="' . $mapurl . '" alt="location" width="' . $width . '" height="' . $height . '">';
                break;
        }
    }
}
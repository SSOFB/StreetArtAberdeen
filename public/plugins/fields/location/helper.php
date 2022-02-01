<?php
/**
 * @copyright   Copyright (C) 2005 - 2017 Michael Richey. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

abstract class plgFieldsLocationHelper {
    static $loaded = false;
    static $registered = array();
    static function register($id) {
        self::$registered[] = $id;
    }
    static function loadMapsAPI($params, $debug) {
        if (static::$loaded) {
            return;
        }
        $doc = JFactory::getDocument();
        $urlvars = array();
        if ($params->get('searchbox', false)) {
            $urlvars[] = 'libraries=places';
            JText::script('PLG_FIELDS_LOCATION_SEARCHBOX_PLACEHOLDER');
        }
        $urlvars[] = 'key=' . $params->get('apikey', false);
        $urlvars[] = 'sensor=true';
        $scripturl = '//maps.googleapis.com/maps/api/js?' . implode('&', $urlvars);
        if (!isset($doc->_scripts[$scripturl])) {
            $doc->addScript($scripturl);
            #$doc->addScript(JURI::root(true) . '/media/plg_fields_location/plg_fields_location' . ($debug ? '' : '.min') . '.js');
            $doc->addScript(JURI::root(true) . '/media/plg_fields_location/plg_fields_location.js');
        }
        static::$loaded = true;
    }

    static function createStaticMapURL($pluginparams, $latlon, $zoom, $width, $height) {
        $size = array(str_replace(array('px', 'auto'), array('', '400'), $width), str_replace(array('px', 'auto'), array('', '300'), $height));
        $url = '//maps.googleapis.com/maps/api/staticmap?';
        $params = array();
        $params[] = 'center=' . $latlon;
        $params[] = 'zoom=' . $zoom;
        $params[] = 'size=' . implode("x", $size);
        $params[] = 'key=' . $pluginparams->get('staticapikey', false);
        $params[] = 'markers=' . $latlon;
        return $url . implode("&", $params);
    }

}
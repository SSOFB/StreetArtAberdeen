<?php 
/*
Script to bulk add images to the website
https://github.com/SSOFB/StreetArtAberdeen/blob/main/public/templates/street_art_aberdeen/html/com_content/form/edit.php

https://docs.joomla.org/J4.x:Joomla_Core_APIs

mkdir images_to_add



*/

require_once("secret.php");

foreach (glob("images_to_add/*.jpg") as $filename) {
    echo "$filename size " . filesize($filename) . "\n";

    $exif_data = exif_read_data($filename, 0, true);

    print_r($exif_data);
    /*
    foreach ($exif as $key => $section) {
        foreach ($section as $name => $val) {
            echo "$key.$name: $val<br />\n";
        }
    }
    */

    echo "\n";

    # TODO: upload the image


    # https://docs.joomla.org/J4.x:Joomla_Core_APIs#Create_Article


    # TODO: added geo data

    # TODO: call Joomla API
}
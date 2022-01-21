<?php 
/**
 * This is a helper file for logic that is needed in multiple parts of the template, and possibly elsewhere
 *
 * Usage:
 * Register it like...
 * JLoader::register('saa_helper', 'templates/street_art_aberdeen/html/saa_helper.php'); 
 * Call functions like...
 * saa_helper::tester("hello");
 * ...or... 
 * saa_helper::check_image("image-field-file_id313_2022-01-20_22-32-44_2247.jpeg");
 */

class saa_helper{

    # fixed params
    public $image_base = JPATH_ROOT . "/images/";
    public $small_width = 100;
    public $small_height = 100;
    public $large_width = 500;
    public $large_height = 500;

    /**
     * tester
     * 
     * @param string    test string
     * 
     * @return string   output string
     */
    public static function tester($test_value){
        return "tester says " . $test_value;
    }



    /**
     * check_image
     * 
     * @param string    filename
     * 
     * @return bool     true for success
     */
    public static function check_image( $input_filename ) {
        JFactory::getApplication()->enqueueMessage("check_image: " . $input_filename);

        $input_filename = basename( $input_filename );
        # other params
        $input_full_filename = $this->image_base . $input_filename;
        $output_small_filename = "small_" . $input_filename;
        $output_large_filename = "large_" . $input_filename;
        $output_small_full_filename = $this->image_base . $output_small_filename;
        $output_large_full_filename = $this->image_base . $output_large_filename;
        #$small_dimension = $small_width . "x" . $small_height;
        #$large_dimension = $large_width . "x" . $large_height;

        # check if the files exists
        if ( !file_exists( $input_full_filename ) ) {
            JFactory::getApplication()->enqueueMessage("File not found: " . $input_full_filename);
            return false;
        }
        /*
        # create a small one
        if ( !file_exists( $output_small_full_filename ) ) {
            $image = new JImage($input_full_filename);
            $image->resize( $this->small_width, $this->small_height, false, JImage::SCALE_INSIDE);              
            $image->toFile($output_small_full_filename);
            JFactory::getApplication()->enqueueMessage("Created small file: " . $output_small_full_filename);
        }

        # create a big one
        if ( !file_exists( $output_large_full_filename ) ) {
            $image = new JImage($input_full_filename);
            $image->resize( $this->large_width, $this->large_height, false, JImage::SCALE_INSIDE);              
            $image->toFile($output_large_full_filename);
            JFactory::getApplication()->enqueueMessage("Created large file: " . $output_large_full_filename);
        }
        */

        return true;

        
/*
    # see if the file exists
    if ( !file_exists($thumb_path) ) {
        # nope
        $image = new JImage($image_path);
        $image->resize( $thumb_width, $thumb_height, false, JImage::SCALE_INSIDE);
        if ( !file_exists( dirname($thumb_path) ) ) {
            mkdir( dirname($thumb_path) );        
        }               
        $image->toFile($thumb_path);
    }
    */

    }




}
?>
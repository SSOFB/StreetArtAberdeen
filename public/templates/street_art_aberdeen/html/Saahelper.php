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

class Saahelper{

    # fixed params
    const image_url = "/images/";
    const image_path = JPATH_ROOT . "/images/";
    const small_width = 100;
    const small_height = 100;
    const large_width = 500;
    const large_height = 500;




    public function onAfterInitialise(){
        JLoader::registerPrefix('Saahelper', JPATH_ROOT . '/templates/street_art_aberdeen/html');
    }



    /**
     * tester
     * 
     * @param string    test string
     * 
     * @return string   output string
     */
    public static function tester($test_value){
        return "tester says " . $test_value . " static test: " . self::small_width;
    }



    /**
     * check_image
     * 
     * @param string    filename
     * 
     * @return bool     true for success
     */
    public static function check_image( $input_filename ) {
        #JFactory::getApplication()->enqueueMessage("check_image: " . $input_filename);

        # check if the files exists
        if ( strlen( $input_filename ) == 0 ) {
            JFactory::getApplication()->enqueueMessage("No image: " . $input_filename);
            # SELECT * FROM `s3ib7_fields_values` WHERE `field_id`=6 AND `value`="";
            return false;
        }

        $input_filename = basename( $input_filename );
        # other params
        $input_full_filename = self::image_path . $input_filename;
        $output_small_filename = "small_" . $input_filename;
        $output_large_filename = "large_" . $input_filename;
        $output_small_full_filename = self::image_path . $output_small_filename;
        $output_large_full_filename = self::image_path . $output_large_filename;
        #$small_dimension = $small_width . "x" . $small_height;
        #$large_dimension = $large_width . "x" . $large_height;

        # check if the files exists
        if ( !file_exists( $input_full_filename ) ) {
            JFactory::getApplication()->enqueueMessage("File not found: " . $input_full_filename);
            return false;
        }

        # create a small one
        if ( !file_exists( $output_small_full_filename ) ) {
            $image = new JImage($input_full_filename);
            $image->resize( self::small_width, self::small_height, false, JImage::SCALE_INSIDE);              
            $image->toFile($output_small_full_filename);
            JFactory::getApplication()->enqueueMessage("Created small file: " . $output_small_full_filename);
        }

        # create a big one
        if ( !file_exists( $output_large_full_filename ) ) {
            $image = new JImage($input_full_filename);
            $image->resize( self::large_width, self::large_height, false, JImage::SCALE_INSIDE);              
            $image->toFile($output_large_full_filename);
            JFactory::getApplication()->enqueueMessage("Created large file: " . $output_large_full_filename);
        }

        return true;
    }



    /**
     * clear_out_image
     * 
     * @param string    filename
     * 
     * @return string   feedback text
     */
    public static function clear_out_image( $input_filename ) {
        $feedback = "Deleted files: \n";
        $input_filename = basename( $input_filename );
        $image_pattern = self::image_path . "*_" .  $input_filename;
        foreach (glob($image_pattern) as $filename) {
            echo "$filename size " . filesize($filename) . "\n";
            unlink( $filename );
            $feedback .= "- " . $filename . "\n";
        }
        return $feedback;
    }


    /**
     * small_image
     * 
     * @param string    filename
     * 
     * @return string   small filename
     */
    public static function small_image( $input_filename ) {
        $input_filename = basename( $input_filename );
        $small_filename = self::image_url . "small_" . $input_filename;
        return $small_filename;
    }

    
}
?>
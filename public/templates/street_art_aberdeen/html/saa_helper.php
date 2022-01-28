<?php 
/**
 * This is a helper file for logic that is needed in multiple parts of the template, and possibly elsewhere
 *
 * Some issues with scope, see https://joomla.stackexchange.com/questions/31868/loading-a-helper-file-in-joomla-4
 * 
 * Usage:
 * Register it like...
 * JLoader::register('saa_helper', 'templates/street_art_aberdeen/html/saa_helper.php'); 
 * Call functions like...
 * saa_helper::tester("hello");
 * ...or... 
 * saa_helper::check_image("image-field-file_id313_2022-01-20_22-32-44_2247.jpeg");
 */

#namespace Saa_helper; # not sure about this bit
namespace Joomla\CMS\Saa_helper;

use Joomla\CMS\Factory;
#use Joomla\CMS\JImage;
class Saa_helper{

    # fixed params
    const image_url = "/images/";
    const image_path = JPATH_ROOT . "/images/";
    const small_width = 500;
    const small_height = 100;
    const large_width = 500;
    const large_height = 500;

    # not sure if the onAfterInitialise is needed
    public function onAfterInitialise(){
        JLoader::registerPrefix('Saa_helper', JPATH_ROOT . '/templates/street_art_aberdeen/html');
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

        self::ilog("input_filename: " . $input_filename);

        # check if the files exists
        if ( strlen( $input_filename ) == 0 ) {
            # it should always be there, can check with a SELECT * FROM `s3ib7_fields_values` WHERE `field_id`=6 AND `value`="";
            Factory::getApplication()->enqueueMessage("No image: " . $input_filename);
            return false;
        }

        $input_filename = basename( $input_filename );
        # other params
        $input_full_filename = self::image_path . $input_filename;
        $output_small_filename = "small_" . $input_filename;
        $output_large_filename = "large_" . $input_filename;
        $output_pin_filename = "pin_" . str_replace(Array(".jpg", ".jpeg"), ".png", $input_filename);
        $output_small_full_filename = self::image_path . $output_small_filename;
        $output_large_full_filename = self::image_path . $output_large_filename;
        $output_pin_full_filename = self::image_path . $output_pin_filename;


        # check if the files exists
        if ( !file_exists( $input_full_filename ) ) {
            Factory::getApplication()->enqueueMessage("File not found: " . $input_full_filename);
            return false;
        }

        # create a small one
        if ( !file_exists( $output_small_full_filename ) ) {
            #$image = new JImage($input_full_filename);
            #$image->resize( self::small_width, self::small_height, false, JImage::SCALE_INSIDE);              
            #$image->toFile($output_small_full_filename);



            Factory::getApplication()->enqueueMessage("Created small file: " . $output_small_full_filename);
        }

        /*
        # create a big one
        if ( !file_exists( $output_large_full_filename ) ) {
            $image = new Image($input_full_filename);
            $image->resize( self::large_width, self::large_height, false, JImage::SCALE_INSIDE);              
            $image->toFile($output_large_full_filename);
            JFactory::getApplication()->enqueueMessage("Created large file: " . $output_large_full_filename);
        }
        */

        # create a the pin one
        if ( !file_exists( $output_pin_full_filename ) ) {
            # add an overlay
            $width = 40; 
            $height = 40; 
            
            # get the art image
            # TODO: check if it's a jpeg, we can't really assume this: output_pin_full_filename or  output_small_full_filename
            $bottom_image = self::get_image($output_pin_full_filename); 
            $bottom_image = imagescale($bottom_image, $width - 2, 26); 
            
            # get the 
            $top_image = imagecreatefrompng(JPATH_BASE . "/templates/street_art_aberdeen/images/pin.png"); 
            imagesavealpha($top_image, true); 
            imagealphablending($top_image, true); 

            # create the new image
            $pin_image = imagecreatetruecolor($width, $height);
            imagealphablending($pin_image, true); 
            $transparency = imagecolorallocatealpha($pin_image, 0, 0, 0, 127);
            imagefill($pin_image, 0, 0, $transparency);
            imagesavealpha($pin_image, true); 

            # add the art image to the new image
            imagecopy($pin_image, $bottom_image, 1, 1, 0, 0, $width - 2, 26); 
            # add the pin overlay
            imagecopy($pin_image, $top_image, 0, 0, 0, 0, $width, $height); 
            # output it
            imagepng($pin_image, $output_pin_full_filename);
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
        $feedback = "Deleted files for " . $input_filename . ": \n";
        $input_filename = basename( $input_filename );
        $image_pattern = self::image_path . "*_" .  $input_filename;
        foreach (glob($image_pattern) as $filename) {
            echo "$filename size " . filesize($filename) . "\n";
            unlink( $filename );
            $feedback .= " - " . $filename . "\n";
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

     /**
     * large_image
     * 
     * @param string    filename
     * 
     * @return string   large filename
     */
    public static function large_image( $input_filename ) {
        $input_filename = basename( $input_filename );
        $small_filename = self::image_url . "large_" . $input_filename;
        return $small_filename;
    }  


     /**
     * pin_image
     * 
     * @param string    filename
     * 
     * @return string   pin filename
     */
    public static function pin_image( $input_filename ) {
        $input_filename = basename( $input_filename );
        $small_filename = self::image_url . "pin_" . str_replace(Array(".jpg", ".jpeg"), ".png", $input_filename);
        return $small_filename;
    } 


     /**
     * get image
     * 
     * @param string    filename
     * 
     * @return GdImage  image obj
     */
    public static function get_image( $input_filename ) {
        self::ilog("input_filename: " . $input_filename);
        $image_info = getimagesize($input_filename);
        self::ilog("image_info: " . print_r( $image_info, TRUE) );

        $image_type = $image_info[2];
        if( $image_type == IMAGETYPE_JPEG ) {
           $image_data = imagecreatefromjpeg($input_filename);
        } elseif( $image_type == IMAGETYPE_GIF ) {
           $image_data = imagecreatefromgif($input_filename);
        } elseif( $image_type == IMAGETYPE_PNG ) {
           $image_data = imagecreatefrompng($input_filename);
        }        
        return $image_data;
    } 



    /**
    * very simple logging function
    *
    * @param   string    the log string
    * @param   int       log level
    */    
    public static function ilog($log_string, $level=1) {
        $logging_level = 0;
        
        if ( $level > $logging_level ) {
            $log_file = JPATH_ADMINISTRATOR . '/logs/saa_helper.log';
            $fh = fopen($log_file, 'a') or die();
            # if it's a app designer call, also write it to that log
            $calling_function = debug_backtrace()[1]['function'];
            $log_string = date("Y-m-d H:i:s") . " : " . $calling_function . " : " . $log_string . "\n";
            fwrite($fh, $log_string);
            fclose($fh);  
        }
    }  	


}
?>
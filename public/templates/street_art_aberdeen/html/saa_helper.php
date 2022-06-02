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
 * saa_helper::check_image("large_image-field-file_id602_2022-03-08_16-18-03_7101.jpeg");
 */

#namespace Saa_helper; # not sure about this bit
namespace Joomla\CMS\Saa_helper;

use Joomla\CMS\Factory;
use Joomla\CMS\JImage;
class Saa_helper{

    # fixed params
    const image_url = "/images/";
    const image_path = JPATH_ROOT . "/images/";
    const small_width = 400;
    const small_height = 120;
    const large_width = 1400;
    const large_height = 1000;
    const ig_width = 1080;
    const ig_height = 1080;

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
            #Factory::getApplication()->enqueueMessage("No image: " . $input_filename);
            self::ilog("input_filename, no file, value is empty");
            return false;
        }

        $input_filename = basename( $input_filename );
        # other params
        $input_full_filename = self::image_path . $input_filename;
        $output_small_filename = "small_" . $input_filename;
        $output_large_filename = "large_" . $input_filename;
        $output_pin_filename = "pin_" . str_replace(Array(".jpg", ".jpeg"), ".png", $input_filename);
        $output_ig_filename = "ig_" . $input_filename;
        $output_small_full_filename = self::image_path . $output_small_filename;
        $output_large_full_filename = self::image_path . $output_large_filename;
        $output_pin_full_filename = self::image_path . $output_pin_filename;
        $output_ig_full_filename = self::image_path . $output_ig_filename;


        # check if the files exists
        if ( !file_exists( $input_full_filename ) ) {
            #Factory::getApplication()->enqueueMessage("File not found: " . $input_full_filename);
            self::ilog("input_filename, no file, file doesn't exist");
            return false;
        }

        # create a small one
        if ( !file_exists( $output_small_full_filename ) ) {
            $image = self::get_image($input_full_filename); 
            $image = self::rezise_image($image, self::small_width, self::small_height );
            $out_result = imagejpeg($image, $output_small_full_filename);
            if ($out_result) {
                self::ilog("created small file: " . $output_small_full_filename);
            } else {
                self::ilog("failed to create small file: " . $output_small_full_filename);
            }
        } else {
            self::ilog("small file exists already: " . $output_small_full_filename);
        }

        # create a big one
        if ( !file_exists( $output_large_full_filename ) ) {
            $image = self::get_image($input_full_filename); 
            $image = self::rezise_image($image, self::large_width, self::large_height );
            $out_result = imagejpeg($image, $output_large_full_filename);
            if ($out_result) {
                self::ilog("created large file: " . $output_large_full_filename);
            } else {
                self::ilog("failed to create large file: " . $output_large_full_filename);
            }
            
        } else {
            self::ilog("large file exists already: " . $output_large_full_filename);
        }

        # create a the pin one
        if ( !file_exists( $output_pin_full_filename ) ) {
            # add an overlay
            $width = 60; 
            $height = 60; 
            
            # get the art image
            $bottom_image = self::get_image($input_full_filename); 
            $bottom_image = imagescale($bottom_image, $width - 2, 42); 
            
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
            imagecopy($pin_image, $bottom_image, 1, 1, 0, 0, $width - 2, 42); 
            # add the pin overlay
            imagecopy($pin_image, $top_image, 0, 0, 0, 0, $width, $height); 
            # output it
            imagepng($pin_image, $output_pin_full_filename);
            self::ilog("created pin file: " . $output_pin_full_filename);
        } else {
            self::ilog("pin file exists already: " . $output_pin_full_filename);
        }

 
        # create the ig one
        if ( !file_exists( $output_ig_full_filename ) ) {

            #list($width, $height, $type, $attr) = getimagesize($input_full_filename);           
            # is it taller or wide
            #$is_tall = ($height > $width) ? true : false;

            $image = self::get_image($input_full_filename); 
            $image = self::rezise_image($image, self::ig_width, self::ig_height );
            $image_width = imagesx($image);
            $image_height = imagesy($image);

            self::ilog("image_width: " . $image_width);
            self::ilog("image_height: " . $image_height);

            $is_tall = ($image_height > $image_width) ? true : false;

            if ( $is_tall ) {
                self::ilog("it's tall");
                $background_image = imagecreatefromjpeg(JPATH_BASE . "/templates/street_art_aberdeen/images/ig_background_tall.jpg"); 
                $width = $image_width;
                $height = 1080;
                $x = (1080 - $image_width) / 2;
                $y = 0;
            } else {
                self::ilog("it's wide");
                $background_image = imagecreatefromjpeg(JPATH_BASE . "/templates/street_art_aberdeen/images/ig_background_wide.jpg"); 
                $width = 1080;
                $height = $image_height;
                $x = 0;
                $y = (1080 - $image_height) / 2;
            } 

            # create the new image
            $ig_image = imagecreatetruecolor(1080, 1080);

            # add the background to the new image
            imagecopy($ig_image, $background_image, 0, 0, 0, 0, 1080, 1080); 

            # add the art image to the new image
            imagecopy($ig_image, $image, $x, $y, 0, 0, $width, $height); 

            # output it
            imagejpeg($ig_image, $output_ig_full_filename);

            self::ilog("created ig file: " . $output_ig_full_filename);
        } else {
            self::ilog("ig file exists already: " . $output_ig_full_filename);
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
        self::ilog("Deleted files for " . $input_filename );
        $input_file_info = pathinfo( $input_filename );
        $image_pattern = self::image_path . "*_" .  $input_file_info['filename'] . ".*";
        self::ilog("With the pattern: " . $image_pattern . ":");
        foreach (glob($image_pattern) as $filename) {
            #echo $filename . " size: " . filesize($filename) . "\n";
            unlink( $filename );
            self::ilog(" - " . $filename);
        }
        return "cleared out image: " . $input_filename;
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
        $this_filename = self::image_url . "small_" . $input_filename;
        return $this_filename;
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
        $this_filename = self::image_url . "large_" . $input_filename;
        return $this_filename;
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
        $this_filename = self::image_url . "pin_" . str_replace(Array(".jpg", ".jpeg"), ".png", $input_filename);
        return $this_filename;
    } 

     /**
     * ig_image
     * 
     * @param string    filename
     * 
     * @return string   ig filename
     */
    public static function ig_image( $input_filename ) {
        $input_filename = basename( $input_filename );
        $this_filename = self::image_url . "ig_" . $input_filename;
        return $this_filename;
    }  



     /**
     * get image
     * 
     * @param string    filename
     * 
     * @return \GdImage  image obj
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
     * resize image
     * this function resizes images so they'd fit in a maxwidth x maxheight box, keeping the aspect ratio the same
     * 
     * @param   \GdImage  image obj
     * @param   int      max width of the box the image will fit in
     * @param   int      max height of the box the image will fit in
     * 
     * @return  \GdImage  image obj
     */
    public static function rezise_image( $image_obj, $max_width, $max_height ) {
        $input_width = imagesx($image_obj);
        $input_height = imagesy($image_obj);

        self::ilog("max size: " . $max_width . "x" .  $max_height );
        $max_ratio = $max_width / $max_height;
        self::ilog("max_ratio: " . $max_ratio );

        self::ilog("input size: " . $input_width . "x" .  $input_height );        
        $input_ratio = $input_width / $input_height;
        self::ilog("input_ratio: " . $input_ratio );       
        
        if($input_ratio > $max_ratio) {
            # wider than max, so width is the limiter
            $height = round($max_width / $input_ratio);
            $image_obj = imagescale($image_obj, $max_width, $height);
            self::ilog("wide image: " . $max_width . "x" . $height );  
        } else {
            # taller than max, so height is the limiter
            $width = round($max_height * $input_ratio);
            $image_obj = imagescale($image_obj, $width, $max_height);
            self::ilog("tall image: " . $width . "x" . $max_height ); 
        }        
        return $image_obj;
    } 


    /**
     * Get a field value
     * 
     * @param   int     article_id
     * @param   int     field_id, 1: medium, 2: location, 3: year created, 6: photo, 9: state
     * 
     * @return  string  the field value
     */
    public static function get_field_value($article_id, $field_id) {

        $db = JFactory::getDbo();
        
            
        # get the value 
        $query = $db->getQuery(true);
        $query->select($db->quoteName('value'));
        $query->from($db->quoteName('#__fields_values'));
        $query->where($db->quoteName('field_id') . " = " . $db->quote($field_id));
        $query->where($db->quoteName('item_id') . " = " . $db->quote($article_id));
        $db->setQuery($query);
        $value = $db->loadResult();

        if ( $value === null) {
            # if there is no value, get the default
            $query = $db->getQuery(true);
            $query->select($db->quoteName('default_value'));
            $query->from($db->quoteName('#__fields'));
            $query->where($db->quoteName('id') . " = " . $db->quote($field_id));
            $db->setQuery($query);
            $value = $db->loadResult();
        }

        return $value;
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
            $pid = getmypid();
            $log_file = JPATH_ADMINISTRATOR . "/logs/saa_helper_" . date("Y-m-d_H-i")  . "_p" . $pid . ".log";
            $fh = fopen($log_file, 'a') or die();
            $calling_function = debug_backtrace()[1]['function'];
            $log_string = date("Y-m-d H:i:s") . " : " . $calling_function . " : " . $log_string . "\n";
            fwrite($fh, $log_string);
            fclose($fh);  
        }
    }  	

    /**
    * very simple logging function
    *
    * @param   string    the log string
    * @param   int       log level
    */    
    public static function elog($log_string, $level=1) {
        $logging_level = 0;
        
        if ( $level > $logging_level ) {
            $log_file = JPATH_ADMINISTRATOR . "/logs/saa_error_" . date("Y-m-d")  . ".log";
            $fh = fopen($log_file, 'a') or die();
            $log_string = date("Y-m-d H:i:s") . " : : " . $log_string . "\n";
            fwrite($fh, $log_string);
            fclose($fh);  
        }
    }  

}
?>
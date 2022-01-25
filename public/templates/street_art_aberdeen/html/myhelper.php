<?php 
/**
 * This is a helper file
 * Usage:
 * Register it like...
 * JLoader::register('myhelper', 'templates/mytemplate/html/myhelper.php'); 
 * Call functions like...
 * myhelper::tester("hello");
 */
#namespace Myhelper; # not sure about this bit
namespace Joomla\Plugin\System\Saaconsole\Console;

class MyHelper{
    /**
     * tester
     * @param string    test string
     * @return string   output string
     */
    public static function tester($test_value){
        return "tester says " . $test_value;
    }
}
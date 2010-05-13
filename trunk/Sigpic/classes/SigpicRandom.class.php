<?php
/* Contains means and methods for doing random stuff.
 *
 * @package Sigpic
 * @author Geir Andre Halle
 * @version v0.9.8
 * @copyright Copyright (c) 2009, Geir Andre Halle
 * @license http://www.gnu.org/licenses/gpl.txt
 */
class SigpicRandom {
    /*
     * @param string $filename Filename with path
     * $return string Random line from specified file
     */
    public static function lineFromFile($filename) {
        $lines = file($filename) ;
	return $lines[array_rand($lines)] ;
    }
}
?>

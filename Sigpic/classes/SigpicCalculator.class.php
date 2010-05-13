<?php
/* Calculator class containing various methods for converting values.
 *
 * @package Sigpic
 * @author Geir Andre Halle
 * @version v0.9.8
 * @copyright Copyright (c) 2009, Geir Andre Halle
 * @license http://www.gnu.org/licenses/gpl.txt
 */
class SigpicCalculator {

	/*
	 * Detect filetype
	 *
	 * @param string $file Filename with or without url/path
	 * @return string Filetype based on name
	 */
	public static function filetype($file)
	{
            if (strpos($file,'ache/')) {
                // Need to decode base64 from cache to get original filename
                $file = base64_decode(substr($file,strrpos($file,'/') + 1));
            }
                $type = trim(strtolower(strrev(strtok(strrev($file),'.'))));
            	return $type;
	}
}
?>

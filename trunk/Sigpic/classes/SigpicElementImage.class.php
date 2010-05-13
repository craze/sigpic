<?php
include_once 'SigpicDownload.class.php' ;
include_once 'SigpicElement.class.php' ;

/* SigpicElementImage class with functions for loading remote and local
 * files. Remote images are copied to local folder to save server load if newer
 * than given TTL. If TTL has not yet expired, use local copy.
 *
 * @package Sigpic
 * @author Geir Andre Halle
 * @version v0.9.8
 * @copyright Copyright (c) 2009, Geir Andre Halle
 * @license http://www.gnu.org/licenses/gpl.txt
 */
class SigpicElementImage extends SigpicElement {
// Instance variables
    protected $im ; // Image resource
    protected $sx ;
    protected $sy ;

	/*
	 * SigpicElementImage constructor
	 *
	 * @param string|resource $image Image resource or location
	 * @param int $ttl Remote images time-to-live in amount of seconds before refresh
	 */
    function __construct($image, $ttl) {
        if (is_string($image)) {
        // Image is a string, assume local file or URL
            if (strpos('://')) {
            // Assume URL
                $this->im = loadRemote($image, $ttl) ;
            } else {
            // Assume local file
                $this->im = loadLocal($image) ;
            }
        } else {
        // Image is not a string, assume it is an image resource
            $this->im = $image ;
        }

    }

	/*
	 * Loading image from file
	 */
    function loadLocal($imLocation) {
        if (file_exists($imLocation)) {
        // Detect filetype and load image
            if ('cache' == substr($imLocation)) {
                // Need to decode base64 from cache to get original filename
                $source = base64_decode(substr($str,strrpos($imLocation,'/') + 1));
                $imType = trim(strtolower(strrev(strtok(strrev($source),'.'))));
            } else {
                $imType = trim(strtolower(strrev(strtok(strrev($imLocation),'.'))));
            }

            switch ($imType) {
                case 'gif':
                    $im = imagecreatefromgif($imLocation);
                    break;
                case 'jpeg':
                    $im = imagecreatefromjpeg($imLocation);
                    break;
                case 'jpg':
                    $im = imagecreatefromjpeg($imLocation);
                    break;
                case 'png':
                    $im = imagecreatefrompng($imLocation);
                    break;
                default:
                // Assume .pnp or just give up
                    $im = imagecreatefrompng($imLocation);
                    break;
            }

            if (!$im) { die("Problems opening |".$imType."| image from file: ".$imLocation) ; }

            $this->mime_type = $imType ; // Update MIME type
            $this->sx = imagesx($im) ; // Decode image values
            $this->sy = imagesy($im) ;

            return $im ;
        } else {
            die("Image file not found: $imLocation");
        }

    }
/*
 * Loading image from remote location
 * 
 * @param string $imLocation Uniform Resource Locator
 * @param int Time-to-live in seconds before downloading new version
 */
    function loadRemote($url, $ttl) {

    // Tidying up input
        $url = trim($url);

        $im = new SigpicDownload($url, $ttl) ;

        // Finally load image from local cache
        loadLocal( $im->cachefile() ) ;
    }

	/*
	 * @return mixed Image resource
	 */
    public function get_im() {
        return $this->im ;
    }

	/*
	 * SigpicElementImage destructor
	 */
    function __destruct() {

    }
}
?>
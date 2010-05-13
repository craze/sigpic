<?php
include_once 'classes/SigpicCalculator.class.php' ;
include_once 'classes/SigpicElementImage.class.php' ;
include_once 'classes/SigpicElementText.class.php' ;
/*
 * Sigpic is the main class in the Sigpic suite, holding the
 * bits and pieces together.
 *
 * @package Sigpic
 * @author Geir Andre Halle
 * @version v0.9.8
 * @copyright Copyright (c) 2009, Geir Andre Halle
 * @license http://www.gnu.org/licenses/gpl.txt
 */
class Sigpic {
// Initializing some variables used in Sigpic
    private $im ;                   // Sigpic canvas placeholder
    private	$sx     = 0 ;           // Sigpic empty width
    private $sy 	= 0 ;           // Sigpic empty height
    private $mime_type  = 'png' ;   // Default image type

	/*
	 * Sigpic constructor with background from file
         *
         * @param string $bgImagefile Backgrund image filename
	 */
    public function __construct($bgImagefile) {
        if (file_exists($bgImagefile)) {
        // Detect filetype and load image
            $this->im	= $this->imageCreateFromFile($bgImagefile) ;
            $this->mime_type = SigpicCalculator::filetype($bgImagefile) ;

            // Extract dimensions
            $this->sx = imagesx($this->im) ;
            $this->sy = imagesy($this->im) ;
        }
    }

	/*
	 * Add an image from location or using a resource.
	 *
	 *  @param resource|string $image Assumes image resource or location string
	 *  @param int $pos_x Horizontal position
	 *  @param int $pos_y Vertical position
         *  @param int $ttl Time-to-live amount of seconds before cache refresh
         */
    public function addElementImage($image, $pos_x, $pos_y, $ttl) {
        if (is_string($image)) {
        // Image is a string, assume local file or URL
            if (strpos( $image, '://')) {
            // Assume URL
                $source = new SigpicDownload($image, $ttl) ;
                $im = $this->imageCreateFromFile( $source->cachefile() ) ;
            } else {
            // Assume local file
                $im = $this->imageCreateFromFile($image) ;
            }
        } else {
        // Image is not a string, assume it is an image resource
            $im = $image ;
        }

        // Adjust alignment
        $sx = imagesx($im) ;
        $sy = imagesy($im) ;
        $imxy = $this->alignImage($sx, $sy, $pos_x, $pos_y) ;

        // Commit
        imagecopy($this->im, $im, $imxy[0], $imxy[1], 0, 0, $sx, $sy) ;

    }

	/*
	 * Add text to image.
	 * Position is measured from top left corner by default. Use negative position
	 * values to align element from right, bottom or both.
	 *
	 * @param int $size 	Font size
	 * @param int $angle 	Text angle, counter clockwise from left to right
	 * @param int $pos_x 	Horizontal position
	 * @param int $pos_y 	Vertical position
	 * @param string $hex_color	Hexadecimal color index (HTML-style #0f0f0f)
	 * @param string $fontfile	Font file with relative path
	 * @param string $text		User-defined textstring
	 */
    public function addElementText($size, $angle, $pos_x, $pos_y, $hex_color, $fontfile, $text) {
    // Conditionally create a text object and add to image
        if (file_exists($fontfile)) {
        // Create text
            $txt = new SigpicElementText($size, $angle, $pos_x, $pos_y, $hex_color, $fontfile, $text);
            // Alignment corrections
            $txt_xy = $this->alignText($txt->getSizeX(), $txt->getSizeY(), $pos_x, $pos_y) ;

            // Add text
            imagettftext($this->im, $txt->getSize(), $txt->getAngle(), $txt_xy[0], $txt_xy[1], $this->hex2rgb($txt->getColor()), $txt->getFontfile(), $txt->getText()) ;


        } else {
            die("Font file not found: $fontfile");
        }

    }

	/*
	 * Converting image to true color, scrapping image palette. This is
         * useful since adding images will do so using the background image
         * palette. Intentionally kept as an option instead of having the
         * background loaded as truecolor at all times.
	 */
    public function setTrueColor() {
    // Only do anything if image isn't true color already
        if (!imageistruecolor($this->im)) {
        // Create a new TrueColor canvas
            $truecolor = imagecreatetruecolor($this->sx,$this->sy) ;

            // Copy original background to new
            imagecopy($truecolor, $this->im, 0, 0, 0, 0, $this->sx, $this->sy) ;

            // Replace old with new
            $this->im = $truecolor ;
        }
    }



	/*
	 * Image creator. Function to load an image from file without specifying
         * format.
         *
         * @param string $file Image filename
         * @return mixed Image resource
	 */
    public function imageCreateFromFile($file) {
        if (file_exists($file)) {
        // Detect filetype and load image
            $imType     = SigpicCalculator::filetype($file) ;

            switch ($imType) {
                case 'gif':
                    $im = imagecreatefromgif($file) ;
                    break;
                case 'jpeg':
                    $im = imagecreatefromjpeg($file) ;
                    break;
                case 'jpg':
                    $im = imagecreatefromjpeg($file) ;
                    break;
                case 'png':
                    $im = imagecreatefrompng($file) ;
                    break;
                default:
                // Try .png or give up
                    $im = imagecreatefrompng($file) ;
                    if (!$im) {
                        die("Can't open |".$imType."| image from file: $file");
                    }
                    break;
            }
            return $im ;
        } else { die("Image file not found: $bgImagefile") ;	}

    }

	/*
	 * Send created image to client
	 */
    public function display() {
        header('Content-type: ' . $this->mime_type) ;
        ImagePNG($this->im) ;
    }

	/*
	 * Recalculate position coordinates and return as an array.
	 *
	 * @param int $size_x Horizontal size
	 * @param int $size_y Vertical size
	 * @param int $pos_x Requested horizontal position
	 * @param int $pos_y Requested vertical postition
	 * @return array Array where 0 = Pos_X, 1 = Pos_Y
	 */
    private function alignImage($size_x, $size_y, $pos_x, $pos_y) {
        $pos = array( $pos_x , $pos_y ) ;

        if (substr($pos[0], 0, 1) == '-') {
        // Horizontal adjustment only if it's negative
            $pos[0] = $this->sx - $size_x - abs($pos_x) ;
        }
        if (substr($pos[1], 0, 1) == '-') {
        // Vertical adjustment for negative value
            $pos[1] = $this->sy - $size_y - abs($pos_y) ;
        }

        return $pos ;
    }
	/*
	 * Recalculate position coordinates and return as an array.
	 *
	 * @param int $size_x Horizontal size
	 * @param int $size_y Vertical size
	 * @param int $pos_x Requested horizontal position
	 * @param int $pos_y Requested vertical postition
	 * @return array array( $new_pos_x , $new_pos_y )
	 */
    private function alignText($size_x, $size_y, $pos_x, $pos_y) {
        $pos = array( $pos_x , $pos_y ) ;

        if (substr($pos[0], 0, 1) == '-') {
        // Horizontal adjustment only if it's negative
            $pos[0] = $this->sx - $size_x - abs($pos_x) ;
        }
        if (substr($pos[1], 0, 1) == '-') {
        // Vertical adjustment for negative value (align=bottom)
            $pos[1] = $this->sy - abs($pos_y) ;
        } else {
        // Vertical adjustment for positive value (using top instead of bottom)
            $pos[1] = $pos[1] + $size_y ;
        }

        return $pos ;
    }
	/*
	 * Converting the userfriendly HTML-style hexadecimal to RGB index value
	 *
	 * @param string $hex HTML-style #0099ff hexadecimal
	 * @return int RGB-value
	 */
    public function hex2rgb($hex) {
    // Load colors into array, using 6 last chars thus ignoring #
        $dec	= sscanf(substr($hex,-6), '%2x%2x%2x') ;

        // Decode
        $rgb = imagecolorallocate($this->im , $dec[0], $dec[1], $dec[2]) ;
        return $rgb ;
    }

	/*
	 * Sigpic destruct
	 */
    protected function __destruct() {
        imagedestroy($this->im) ;
    }
}

?>
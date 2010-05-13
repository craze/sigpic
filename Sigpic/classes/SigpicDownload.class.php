<?php
/*
 * SigpicDownload is responsible for downloading remote data to local cache
 *
 * @package Sigpic
 * @author Geir Andre Halle
 * @version v0.9.8
 * @copyright Copyright (c) 2009, Geir Andre Halle
 * @license http://www.gnu.org/licenses/gpl.txt
 */
class SigpicDownload {
    private $cachepath = 'cache/' ;
    private $cachefile = '' ;
    /*
     * @param string $url uniform resource locator
     * @param int $ttl Time-to-live, in seconds between refresh
     */
    function __construct($url, $ttl) {
        $badchars = array(':', '/') ;
        //$this->cachefile = $this->cachepath . str_replace($badchars, '-', $url) ;
        $this->cachefile = $this->cachepath . base64_encode($url) ;
        if (!is_dir($this->cachepath)) {
            die ("Cache folder not found. Try to create manually: $this->cachepath");
        }
        if ( (!file_exists($cachepath)) || ((time() - filemtime($cachepath)) > $ttl) ) {

            // Get remote resource
            $remoteio = curl_init() ;
            curl_setopt($remoteio, CURLOPT_URL, $url) ;
            curl_setopt($remoteio, CURLOPT_HEADER, 0) ;
            curl_setopt($remoteio, CURLOPT_RETURNTRANSFER, true) ;
            $remotecontent = curl_exec($remoteio) ;
            curl_close($remoteio) ;

            // Save resource to local file
            $cacheio = fopen($this->cachefile, 'w');
            fwrite($cacheio, $remotecontent);

            // Clean up
            fclose($cacheio);
        }
    }

    /*
     * @return string Local filename
     */
    public function cachefile() {
        return "$this->cachefile" ;
    }
}
?>
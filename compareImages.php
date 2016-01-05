<?php
/**
 * @author ThaoNv - 2016
 * Fast PHP compare images
 * ---------------------------
 * Extend from: http://compareimages.nikhazy-dizajn.hu/
 * */

class compareImages
{
    public $source = null;
    private $hasString = '';

    function __construct($source)
    {
        $this->source = $source;
    }

    private function mimeType($i)
    {
        /*returns array with mime type and if its jpg or png. Returns false if it isn't jpg or png*/
        $mime = getimagesize($i);
        $return = array($mime[0], $mime[1]);

        switch ($mime['mime']) {
            case 'image/jpeg':
                $return[] = 'jpg';
                return $return;
            case 'image/png':
                $return[] = 'png';
                return $return;
            default:
                return false;
        }
    }

    private function createImage($i)
    {
        /*retuns image resource or false if its not jpg or png*/
        $mime = $this->mimeType($i);
        if ($mime[2] == 'jpg') {
            return imagecreatefromjpeg($i);
        } else
            if ($mime[2] == 'png') {
                return imagecreatefrompng($i);
            } else {
                return false;
            }
    }

    private function resizeImage($source)
    {
        /*resizes the image to a 8x8 squere and returns as image resource*/
        $mime = $this->mimeType($source);
        $t = imagecreatetruecolor(8, 8);
        $source = $this->createImage($source);
        imagecopyresized($t, $source, 0, 0, 0, 0, 8, 8, $mime[0], $mime[1]);
        return $t;
    }

    private function colorMeanValue($i)
    {
        /*returns the mean value of the colors and the list of all pixel's colors*/
        $colorList = array();
        $colorSum = 0;
        for ($a = 0; $a < 8; $a++) {
            for ($b = 0; $b < 8; $b++) {
                $rgb = imagecolorat($i, $a, $b);
                $colorList[] = $rgb & 0xFF;
                $colorSum += $rgb & 0xFF;
            }
        }
        return array($colorSum / 64, $colorList);
    }

    private function bits($colorMean)
    {
        /*returns an array with 1 and zeros. If a color is bigger than the mean value of colors it is 1*/
        $bits = array();
        foreach ($colorMean[1] as $color) {
            $bits[] = ($color >= $colorMean[0]) ? 1 : 0;
        }
        return $bits;

    }

    public function compareWith($tagetImage)
    {
        $tagetString = $this->hasString($tagetImage);
        if ($tagetString) {
            return $this->compareHash($tagetString);
        }
        return 100;
    }

    /**
     * Hash String from image. You can save this string to database for reuse
     * @return String 64 character
     * */
    private function hasString($image)
    {
        $i1 = $this->createImage($image);
        if (!$i1) {
            return false;
        }
        $i1 = $this->resizeImage($image);
        imagefilter($i1, IMG_FILTER_GRAYSCALE);
        $colorMean1 = $this->colorMeanValue($i1);
        $bits1 = $this->bits($colorMean1);
        $result = '';
        for ($a = 0; $a < 64; $a++) {
            $result .= $bits1[$a];
        }
        return $result;
    }

    /**
     * Get current image hash String
     * */
    public function getHasString()
    {
        if ($this->hasString == '') {
            $this->hasString = $this->hasString($this->source);
        }
        return $this->hasString;
    }

    /**
     * Get hash String from image url
     * ex: $imageHash = $this->hasStringImage('http://media.com/image.jpg');
     * */
    public function hasStringImage($image)
    {
        return $this->hasString($image);
    }

    /**
     * Compare current image with an image hash String
     * @return int different rates . if different rates < 10 => duplicate image
     */
    public function compareHash($imageHash)
    {
        $sString = $this->getHasString();
        if (strlen($imageHash) == 64 && strlen($sString) == 64) {
            $diff = 0;
            $sString = str_split($sString);
            $imageHash = str_split($imageHash);
            for($a = 0; $a < 64; $a++) {
                if ($imageHash[$a] != $sString[$a]) {
                    $diff++;
                }
            }
            return $diff;
        }
        return 64;
    }
}

?>
<?php
/**
 * The Class that Creates Thumbnails for IE
 * @copyright (c) 2015, NewsBoard Plugin
 * @package WordPress
 * @subpackage NewsBoard Plugin FREE
 */
class cropImage
{
    public $imgSrc, $myImage, $cropHeight, $cropWidth, $x, $y, $thumb, $imgType, $imgResized;
    
    /**
     * Creates jpeg image.
     */
    private function imgCFjpeg()
    {
        $this->myImage = imagecreatefromjpeg($this->imgSrc); 
    }
    
    /**
     * Creates png image.
     */
    private function imgCFpng()
    {
        $this->myImage = imagecreatefrompng($this->imgSrc); 
    }
    
    /**
     * Creates gif image.
     */
    private function imgCFgif()
    {
        $this->myImage = imagecreatefromgif($this->imgSrc); 
    }
    
    /**
     * Resize our image.
     * @param String $image - The path to the image
     * @param Integer $thumbSizeWidth - Crop width
     * @param Integer $thumbSizeHeight - Crop height
     * @param Integer $resizeWidth - Resize width
     * @param Integer $resizeHeight - Resize height
     */
    public function setImage($image, $thumbSizeWidth, $thumbSizeHeight, $resizeWidth, $resizeHeight)
    {
        $this->imgSrc = str_replace(" ", "%20", $image);
        $this->imgType = strtolower(substr(strrchr($this->imgSrc,"."),1));
        if($this->imgType == 'jpg')
            $this->imgType = 'jpeg';
        list($width, $height) = getimagesize($this->imgSrc);
        $funcCreateFrom = 'imgCF' . $this->imgType; 
        if(is_callable(array(&$this, $funcCreateFrom), true))
            $this->$funcCreateFrom();
        else
            return; 
        $this->cropWidth   = $thumbSizeWidth; 
        $this->cropHeight  = $thumbSizeHeight;
        
        $this->x = ($resizeWidth-$this->cropWidth)/2;
        $this->y = ($resizeHeight-$this->cropHeight)/2; 
        
        $this->imgResized = imagecreatetruecolor($resizeWidth, $resizeHeight); 
        imagecopyresampled($this->imgResized, $this->myImage, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $width, $height); 
    }  
    
    /**
     * Creates our thumbnail
     * @param Integer $thumbSizeWidth - Thumbnail width
     * @param Integer $thumbSizeHeight - Thumbnail height
     */
    public function createThumb($thumbSizeWidth, $thumbSizeHeight)
    {
        $this->thumb = imagecreatetruecolor($thumbSizeWidth, $thumbSizeHeight); 
        imagecopyresampled($this->thumb, $this->imgResized, 0, 0,$this->x, $this->y, $thumbSizeWidth, $thumbSizeHeight, $this->cropWidth, $this->cropHeight); 
    }
    
    /**
     * Visualize our new image.
     */
    public function renderImage()
    {              
        header('Content-type: image/' . $this->imgType . '');
        $funcImage = 'imgT' . $this->imgType; 
        $this->$funcImage();
        imagedestroy($this->thumb); 
    }
    
    /*
     * Creates the new jpeg image
     */
    private function imgTjpeg()
    {
        imagejpeg($this->thumb, null, 100);
    }
    
    /*
     * Creates the new png image
     */
    private function imgTpng()
    {
        imagepng($this->thumb, null, 0);
    }
    
    /*
     * Creates the new gif image
     */
    private function imgTgif()
    {
        imagegif($this->thumb, null);
    }
}
$src = $_GET['src'];
$width = $_GET['width'];
$height = $_GET['height'];
$resizeWidth = $_GET['rwidth'];
$resizeHeight = $_GET['rheight'];
$image = new cropImage;
$image->setImage($src, $width, $height, $resizeWidth, $resizeHeight);
$image->createThumb($width, $height);
$image->renderImage();
<?php
/**
*  author : AsterAI Alexandr Baranezky
*/
class SimpleImage {
 
   var $image;
   var $image_type;
   const IMAGE_TYPE_AUTO   = -1 ; 
   
   function load($filename) {
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   
   function save($filename, $image_type=  SimpleImage::IMAGE_TYPE_AUTO, $compression=75, $permissions=null) {
     $image_type = IMAGETYPE_PNG ; 
       if($image_type == SimpleImage::IMAGE_TYPE_AUTO) {
         //Preparing file extension
        $parts = explode(".", $filename);
        $ext = end($parts) ; 
        switch (strtolower($ext)) {
            case "jpg" :  
            case "jpeg" :  
                    $image_type = IMAGETYPE_JPEG ; 
                break; 

            case "png" :  
                    $image_type = IMAGETYPE_GIF ; 
                break; 
            case "png" :  
                $image_type = IMAGETYPE_PNG ; 
                break; 
            default : 
                $image_type = IMAGETYPE_JPEG ; 
        }
 
     }
     if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {

         imagepng($this->image,$filename  );
      }
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }
   
   function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }
   }
   
   function getWidth() {
      return imagesx($this->image);
   }
   
   function getHeight() {
      return imagesy($this->image);
   }
   
   /**
    * Changed size of image by height
    * @param int $height - New Height
    */
   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
    
   /**
    * Changed size of image by width
    * @param int $width -New width
    */
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
   
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
   
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      
      //Create image with transparent background
      imageAlphaBlending($new_image, false);
      imageSaveAlpha($new_image, true);
      
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   }
   
}

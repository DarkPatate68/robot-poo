<?php
session_start();
  $_SESSION['captcha'] = rand(1000,9999);
  
 $img = imagecreatetruecolor(70, 30);
 
 $fill_color=imagecolorallocate($img,255,255,255);
 imagefilledrectangle($img, 0, 0, 70, 30, $fill_color);
 $text_color=imagecolorallocate($img,10,10,10);
 $font = 'polices/Airstream.ttf';
 imagettftext($img, 23, 0, 5,30, $text_color, $font, $_SESSION['captcha']);
 imagefilter($img, IMG_FILTER_EMBOSS);
 imagefilter($img, IMG_FILTER_SMOOTH, 1);
 imagefilter($img, IMG_FILTER_PIXELATE, 2);
 
 header("Content-type: image/jpeg");
 imagejpeg($img);
 imagedestroy($img);
?>
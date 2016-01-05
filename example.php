<?php

/**
 * @author ThaoNv
 * compareImages example
 */
include('compareImages.php');
 
/* Get hash string from image*/
$image1 = 'image1.jpg';
$compareMachine = new compareImages($image1);
$image1Hash = $compareMachine->getHasString(); 
echo "Image 1: <img src='$image1'/><br/>";
echo 'Image 1 Hash :'.$image1Hash.'<br/>';

/* Compare this image with an other image*/
$image2 = 'image2.jpg';
//$diff = $compareMachine->compareWith($image2); //easy
$image2Hash = $compareMachine->hasStringImage($image2); 
$diff = $compareMachine->compareHash($image2Hash); 
echo "Image 2: <img src='$image2'/><br/>";
echo 'Image 2 Hash :'.$image2Hash.'<br/>';
echo 'Different rates (image1 Vs image2): '.$diff;
if($diff>11){
    echo ' => 2 different image';
}
else{
    echo ' => duplicate image';
}
echo '<br/>-------------------------------------------------------------<br/>';

/* Get hash string from image*/
$image3 = 'image3.jpg';
$compareMachine = new compareImages($image3);
$image3Hash = $compareMachine->getHasString(); 
echo "Image 3: <img src='$image3'/><br/>";
echo 'Image 3 Hash :'.$image3Hash.'<br/>';

/* Compare this image with an other image*/
$image4 = 'image4.jpg';
$image4Hash = $compareMachine->hasStringImage($image4); 
$diff = $compareMachine->compareHash($image4Hash); 
echo "Image 4: <img src='$image4'/><br/>";
echo 'Image 4 Hash :'.$image4Hash.'<br/>';
echo 'Different rates (image3 Vs image4): '.$diff;
if($diff>11){
    echo ' => 2 different image';
}
else{
    echo ' => duplicate image';
}
echo '<br/>-------------------------------------------------------------<br/>';
echo 'Different rates (image3 Vs image3): '.$compareMachine->compareWith($image3).'<br/>';
echo 'Different rates (image3 Vs image1): '.$compareMachine->compareWith($image1).'<br/>';
?>
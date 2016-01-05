# CompareImage 
PHP fast image compare lib. Get hash string and detect duplicate image

## Example

```
$image1 = 'image1.jpg';
$image2 = 'image2.jpg';
$compareMachine = new compareImages($image1);
$diff = $compareMachine->compareWith($image2);
```
## Other way
```
$image2Hash = $compareMachine->hasStringImage($image2); 
$diff = $compareMachine->compareHash($image2Hash);
```
## Result
```
If($diff<11){
  echo 'Duplicate Image';
}
else{
  echo '2 different image';
}
```

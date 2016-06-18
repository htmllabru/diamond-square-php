<?php
/*
 * Исходный код на JS взят http://jsfiddle.net/zessx/KhetG/
 * Переписано Алексеем Тарасовым http://htmllab.ru/diamond-square-php/
 * Основная задача примера - переписать реализацию исходного кода на JS с минимум изменений
 */

$size = 8;
$zoom = 5;
$smooth = .9;
$dim = '3d';

$size = pow(2, $size) + 1;
$matrix = [];
$length = $size*$size;
for($i = 0; $i < $length; $i++) $matrix[] = 0;

$width = $height = $width = $height = $size * $zoom;
header ('Content-Type: image/png');
$im = imagecreatetruecolor ( $width , $height );

function toI($x, $y) {
	global $size;
    return $x + $y * $size;
}
function toXY($i) {
    return [
        "x" => floor($i / $size),
        "y" => $i % $size
    ];
}

function genColor($w) {
	global $im;
    $w = ($w - 0.7) * 1700;
    if ($w < 0) return imagecolorallocate($im, 117,212,242);    
    if ($w < 200) return imagecolorallocate($im, 180,198,20);
    if ($w < 500) return imagecolorallocate($im, 199,210,26);
    if ($w < 1000) return imagecolorallocate($im, 254,231,115);
    if ($w < 2000) return imagecolorallocate($im, 249,209,49);
    if ($w < 3000) return imagecolorallocate($im, 236,168,32);
    if ($w < 4000) return imagecolorallocate($im, 176,141,119);
    if ($w < 5000) return imagecolorallocate($im, 199,176,162);
    if ($w < 6000) return imagecolorallocate($im, 224,208,195);
    return imagecolorallocate($im, 255, 255, 255);
}

function drawPoint($x, $y, $s, $step) {
	global $im,$matrix,$smooth,$zoom;
    $points = [];

    if ($step == 'square') {
        if (is_numeric($matrix[toI($x-$s, $y-$s)])) 
			array_push($points,$matrix[toI($x-$s, $y-$s)]);
        if (is_numeric($matrix[toI($x+$s, $y-$s)])) 
			array_push($points,$matrix[toI($x+$s, $y-$s)]);
        if (is_numeric($matrix[toI($x-$s, $y+$s)])) 
			array_push($points,$matrix[toI($x-$s, $y+$s)]);
        if (is_numeric($matrix[toI($x+$s, $y+$s)])) 
			array_push($points,$matrix[toI($x+$s, $y+$s)]);
    } else {
        if (is_numeric($matrix[toI($x-$s, $y)])) 
			array_push($points,$matrix[toI($x-$s, $y)]);
        if (is_numeric($matrix[toI($x+$s, $y)])) 
			array_push($points,$matrix[toI($x+$s, $y)]);
        if (is_numeric($matrix[toI($x, $y-$s)])) 
			array_push($points,$matrix[toI($x, $y-$s)]);
        if (is_numeric($matrix[toI($x, $y+$s)]))
			array_push($points,$matrix[toI($x, $y+$s)]);
    }
    $sum = array_sum($points);
	if($points)
     $avg = $sum / count($points);
	else 
	 $avg = 0;

    $matrix[toI($x, $y)] = $weight = $avg + rand(0, $s*$smooth*100)/100;
	
    $color = genColor($weight);
	
    if ($dim == '2d') {
	  imagefilledrectangle ( $im , $x * $zoom, $y * $zoom, ($x+1) * $zoom, ($y+1) * $zoom , $color );
    } else {  
		imagefilledrectangle ( $im , $x * $zoom, $y * $zoom, $x * $zoom+($x+1) * $zoom, $y * $zoom+($y+1) * $zoom , $color );
    }
}

$s = $size;
while($s > 1)
{ 
    $s = ceil($s / 2);    
    
    for($x=0; $x<$size; $x+=$s*2) {
        for($y=0; $y<$size; $y+=$s*2) {
            drawPoint($x+$s, $y+$s, $s, 'square');
        }
    }
    
    for($x=0; $x<$size; $x+=$s*2) {
        for($y=0; $y<$size; $y+=$s*2) {
            drawPoint($x+$s, $y, $s, 'diamond');
            drawPoint($x+$s, $y+$s*2, $s, 'diamond');
            drawPoint($x, $y+$s, $s, 'diamond');
            drawPoint($x+$s*2, $y+$s, $s, 'diamond');
        }
    }    
}
imagepng($im);
imagedestroy($im);

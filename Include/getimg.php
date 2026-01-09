<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
session_start();
function LoadPNG($imgname,$str)
{
    //$im = @imagecreatefrompng($imgname); /* Attempt to open */
        $im  = imagecreatetruecolor(100, 30); /* Create a blank image */
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $red = imagecolorallocate($im, 125, 222, 122);
        $tc  = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 150, 30, $bgc);
        
		$black = imagecolorallocate($im, 0, 0, 0); 
		$gray = imagecolorallocate($im, 150,150,150); 
		$line = imagecolorallocate($im,233,239,239); 
		$bg = imagecolorallocate($im, 255, 255, 255);

		$textcolor = imagecolorallocate($im, 0, 0, 255);
		
    

		/*for($i=0;$i<=100;$i=$i+10)
		{
		imageline($im,0,0,$i,30,$gray); 
		}*/
		for($i=0;$i<=100;$i=$i+10)
		{
		imageline($im,50,0,$i,15,$gray); 
		}
		for($i=0;$i<=100;$i=$i+10)
		{
		imageline($im,50,30,$i,15,$gray); 
		}
		
		/*for($i=0;$i<=100;$i=$i+10)
		{
		imageline($im,60,0,$i,15,$gray); 
		}*/
		
		/*imageline($im,0,0,100,0,$gray); 
		imageline($im,0,1,100,1,$gray);
*/

// write the string at the top left
		imagestring($im,5, 20, 10, $str, $textcolor);

        //imagestring($im, 1, 5, 5, "test", $black);
    return $im;
}


$str="";
for ($i=1;$i<8;$i++) {
$items = "jkdafjdskfjdfdfkjdsfjdskfjsdfksdjf43yu9243434n$m3m43n43n48493243243b4jh3434438458u43949549584";
$item = rand(0, strlen($items)-1);
$str=$str.$items[$item];
}



//$_SESSION['captchakey']=hash("sha256",$str);
$_SESSION['captchakey'] = $str;

header("Content-Type: image/png");
$img = LoadPNG("captcha.png",$str);
imagepng($img);
?> 
<?php
//Written in 25 August 2017 By SaeedEY.com

function generate_filename()
{
	$url_parts = parse_url($_GET['link']);
	$filename		= str_replace(".", "-", $url_parts["host"]);
	$filename		.= str_replace("/", "-", $url_parts["path"]);
	return trim($filename, "-");
}

function download_headers()
{
	// I'm not sure why, but we can't use zlib
	if(ini_get('zlib.output_compression'))
	{
		ini_set('zlib.output_compression', 'Off');
	}

	// https://stackoverflow.com/a/12388254
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); // required for certain browsers

	header('Content-Type: image/jpeg');
	header('Content-Disposition: attachment; filename="'.generate_filename().'.jpg"');
	header("Content-Transfer-Encoding: binary");
	header('Accept-Ranges: bytes');

	// set up headers for range request or just content length
	// both development and production are currently *not* using
	// http_range
	if(isset($_SERVER['HTTP_RANGE']))
	{
		$range = get_range();
	}
	else
	{
		$new_length=$size;
		header("Content-Length: " . $size);
	}
}

function takeScreenShot($link){
    //website url
    $siteURL = $link;
    if(filter_var($siteURL, FILTER_VALIDATE_URL)){
        $googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$siteURL&screenshot=true");
        $googlePagespeedData = json_decode($googlePagespeedData, true);
        $screenshot = $googlePagespeedData['screenshot']['data'];
        $screenshot = str_replace(array('_','-'),array('/','+'),$screenshot);
				if(empty($_GET['download']) and false)
				{
					header('Content-type: image/jpeg');
				}
				else
				{
					download_headers();
				}
					print base64_decode($screenshot);
        //exit();
    }
}
$url = isset($_GET['link'])?$_GET['link']:false;
if($url !== false)
    takeScreenShot($url);
?>


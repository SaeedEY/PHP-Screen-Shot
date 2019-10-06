<?php
//Written in 25 August 2017 By SaeedEY.com
function takeScreenShot($link){
    //website url
    $siteURL = $link;
    if(filter_var($siteURL, FILTER_VALIDATE_URL)){
			if(!$googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$siteURL&screenshot=true"))
			{
				printf("The following link returned a 404: %s", $siteURL);
				exit;
			}
			if(!$googlePagespeedData = json_decode($googlePagespeedData, true))
			{
				print "Could not decode JSON<br>";
				print json_last_error();
				exit;
			}

			$screenshot = $googlePagespeedData['screenshot']['data'];
			$screenshot = str_replace(array('_','-'),array('/','+'),$screenshot);
			header('Content-type: image/jpeg');
			print base64_decode($screenshot);
			//exit();
    }
}
$url = isset($_GET['link'])?$_GET['link']:false;
if($url !== false)
    takeScreenShot($url);
else
{
	printf('<form action="%s" method="get" id="screenshot_url_form">', $_SERVER["PHP_SELF"]);
	print '<p><label for="link">Website to screenshot</label></p>';
	print '<p><input type="url" id="link" name="link" placeholder="https:://example.org/"></p>';
	print '<p><input type="checkbox" id="download" name="download"><label for="download">Download file</label></p>';
	print '<p><input type="submit" value="Take screenshot"></p>';
	print '<input type="url" id="link" name="link" placeholder="https:://example.org/">';
	print '</form>';
}

?>

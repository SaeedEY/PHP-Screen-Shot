<?php
//Written in 25 August 2017 By SaeedEY.com
function takeScreenShot($link){
    //website url
    $siteURL = $link;
    if(filter_var($siteURL, FILTER_VALIDATE_URL)){
        $googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$siteURL&screenshot=true");
        $googlePagespeedData = json_decode($googlePagespeedData, true);
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
?>
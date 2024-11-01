<?php
session_start();
set_include_path(dirname(__FILE__));
require_once("Zend/Loader.php");
Zend_Loader::loadClass('Zend_Gdata_YouTube');
Zend_Loader::loadClass('Zend_Gdata_AuthSub');

/** 
* Function fnSearchVideos()
* used to call search video api and return the result.
* @strSearchTerms(string) find youtube video
* @intStartLimit(int) start index
* @intEndlimit(int) limit
* @boolCount(bool) allow count
* @return array
*/
function fnSearchVideos($strSearchTerms, $intStartLimit=0,$intEndlimit=50,$boolCount=false)
{
  $objYouTube = new Zend_Gdata_YouTube();
  $objYouTube->setMajorProtocolVersion(2);
  $intEndlimit = ($boolCount== true?50:8);
  $strSearchTerms = str_replace(" ", "+", $strSearchTerms);
  $url = 'https://gdata.youtube.com/feeds/api/videos?q='.urlencode($strSearchTerms).'&key=AI39si6jy5KurWEe0GNW4e83qp-LsHmdUVcvbTdwL5r8sZctQU5bBsKA4I7LFKeoExVlJS28V9rKGgNur3ypoNPOFTHLZxrUKA&orderby=viewCount&start-index='.$intStartLimit.'&max-results='.$intEndlimit;

  try{
    $objVideoFeed = $objYouTube->getVideoFeed($url);
    $arrVideos = fnBuiltVideos($objVideoFeed);
  }catch(Exception $e){

  }
 

  if($boolCount == true)
    return count($arrVideos);
 
  return $arrVideos;
}

/** 
* Function fnBuiltVideos()
* Used to call the fnCreateVideoEntry method which returns video metadata
* @objVideoFeed(object) Video feed object return from youtube
* @return (arr)
*/
function fnBuiltVideos($objVideoFeed)
{
  $arrVideo = array();

  foreach ($objVideoFeed as $videoEntry) {
    $arrVideo[] = fnCreateVideoEntry($videoEntry);
  }

  return $arrVideo;
}

/** 
* Function fnCreateVideoEntry()
* Used to create thumbanil,title,id of video
* @objVideoEntry(object) object of video
* @return (arr)
*/
function fnCreateVideoEntry($objVideoEntry) 
{
  $intInc = 0;
  $arrVideo = array();

  $arrVideo['video'] = $objVideoEntry->getVideoTitle();
  $arrVideo['video_id'] = $objVideoEntry->getVideoId();
  $arrVideoThumbnails = $objVideoEntry->getVideoThumbnails();
 
  foreach($arrVideoThumbnails as $arrVideoThumbnail) {
    $strThumbnail = "thumbnail_".$intInc;
    $arrVideo[$strThumbnail] = $arrVideoThumbnail['url'];
    $intInc++;
  }
  return $arrVideo;
}

/** 
* Function fnGetFeaturedVideo()
* used to call toprated video api and return the result.
* @return array 
*/
function fnGetFeaturedVideo()
{
  $objYouTube = new Zend_Gdata_YouTube();
  $url = 'https://gdata.youtube.com/feeds/api/videos?q=&key=AI39si6jy5KurWEe0GNW4e83qp-LsHmdUVcvbTdwL5r8sZctQU5bBsKA4I7LFKeoExVlJS28V9rKGgNur3ypoNPOFTHLZxrUKA&start-index=1&max-results=8';
  $objTopRatedFeed = $objYouTube->getTopRatedVideoFeed($url);
  
  $arrVideos = fnBuiltVideos($objTopRatedFeed);
  return $arrVideos;
}
?>
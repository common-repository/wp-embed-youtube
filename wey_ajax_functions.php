<?php
/**
* Function _fnWeySearchVideoByAjaxCallBack() 
* Used to serch video from youtube 
* api_youtube.php include functions which provide actual result 
* @return html
*/
function _fnWeySearchVideoByAjaxCallBack(){

    require_once( dirname(__FILE__)."/lib/api_youtube.php" );
    $arrVideos  = array();
    $intLimit = 8;
    $strSearch = $_GET['search'];
    $intPageNo = (isset($_GET['offset'])!=""? $_GET['offset']-1 :0);
    $intStartLimit = ($intLimit * $intPageNo) + 1; 
    $endPage = (isset($_GET['offset'])!=""? $_GET['offset']:1);
    $intEndLimit =  $intLimit * $endPage;

    $arrVideos = fnSearchVideos($strSearch,$intStartLimit,$intEndLimit);
    //$intTotalVideoCount = (int)fnSearchVideos($search,0,50,$count=true);
    $intTotalVideoCount = 50;
    $intTotalPages = ceil($intTotalVideoCount / $intLimit); 
       
    if(!empty($arrVideos)) 
    { ?>
        <div class="row" style="margin-left:0px;">
            <?php 
            if($intTotalPages)
            { ?> 
                <div class="pagination" id="youtubePagination">
                    <ul>
                        <li <?php if($endPage == 1){?>class="disabled"<?php } ?> > <a href="#" >Prev</a></li>
                        <?php for($i=1; $i <= $intTotalPages; $i++){ ?>
                        <li <?php if($endPage == $i){?>class="active"<?php } ?>>
                            <a href="#">
                                <?php echo $i; ?>
                            </a>
                        </li>
                        <?php } ?>
                        <li <?php if($intTotalPages == $endPage){?>class="disabled"<?php } ?> ><a href="#"  >Next</a></li>
                    </ul>
                </div>
            <?php
            } ?>
            <ul class="thumbnails">
                <?php
                foreach ($arrVideos as $video)
                { ?>
                    <li class="span2" title="<?php echo $video['video']; ?>">
                        <div class="thumbnail">
                            <a href="http://www.youtube.com/watch?v=<?php echo $video['video_id']; ?>" target="_blank" class="watchvideo " title="<?php echo $video['video']; ?>"data-placement="center">
                                <img src="<?php echo $video['thumbnail_0']; ?>" class="thumb_img">
                            </a>
                            <div class="caption">
                        	   <p><?php echo substr($video['video'],0,10).".."; ?></p>
                       	    </div>
                            <div style="text-align:center">
                                <input type="radio" name="videoid" value="<?php echo $video['video_id']; ?>" class="checkvideo" >
                            </div>
                        </div>
                    </li>
                <?php 
                } ?>
            </ul>
        </div>
    <?php }else { 
        echo "1";
    }
    die();
}


/**
* Function _fnWeySearchVimeoVideoByAjaxCallBack() 
* Used to serch video from vimeo 
* api_vimeo.php include functions which provide actual result 
* @return html
*/
function _fnWeySearchVimeoVideoByAjaxCallBack(){
    require_once( dirname(__FILE__)."/lib/api_vimeo.php" );
    $arrVideos  = array();
    $videos = array();
    $intLimit = 8;
    if( empty($_GET['search']) )
        $strSearch = 'wordpress';
    else
        $strSearch = $_GET['search'];
    $intPageNo = (isset($_GET['offset'])!=""? $_GET['offset']-1 :0);
    $currentPage = (isset($_GET['offset'])!=""? $_GET['offset'] :0);
    $intStartLimit = ($intLimit * $intPageNo) + 1;
    $endPage = (isset($_GET['offset'])!=""? $_GET['offset']:1);
    $intEndLimit =  $intLimit * $endPage;
    $videos = $vimeo->call('vimeo.videos.search', array('query' => $strSearch, 'page' => $currentPage, 'per_page' => 8));
    $intTotalVideoCount = 50;
    $intTotalPages = ceil($intTotalVideoCount / $intLimit);
    $arrVideos = $videos->videos->video;
    if(!empty($arrVideos))
    { ?>
        <div class="row" style="margin-left:0px;">
            <?php
            if( ($intTotalPages) && !empty($_GET['search']) )
            { ?>
                <div class="pagination" id="vimeoPagination">
                    <ul>
                        <li <?php if($endPage == 1){?>class="disabled"<?php } ?> > <a href="#" >Prev</a></li>
                        <?php for($i=1; $i <= $intTotalPages; $i++){ ?>
                        <li <?php if($endPage == $i){?>class="active"<?php } ?>>
                            <a href="#"><?php echo $i; ?></a>
                        </li>
                        <?php } ?>
                        <li <?php if($intTotalPages == $endPage){?>class="disabled"<?php } ?> ><a href="#"  >Next</a></li>
                    </ul>
                </div>
            <?php
            } ?>
            <ul class="thumbnails vimeoThumbnails">
                <input type="hidden" class="searchOption" value="vimeo">
                <?php
                foreach ($arrVideos as $video)
                { ?>
                    <li class="span2" title="<?php echo $video->title; ?>">
                        <div class="thumbnail">
                            <a href="https://vimeo.com/<?php echo $video->id; ?>" target="_blank" class="watchvideo " title="<?php echo $video->title; ?>"data-placement="center">
                                <img src="<?php echo getVimeoThumb($video->id); ?>" class="thumb_img">
                            </a>
                            <div class="caption">
                               <p><?php echo substr($video->title,0,10).".."; ?></p>
                            </div>
                            <div style="text-align:center">
                                <input type="radio" name="videoid" value="<?php echo $video->id; ?>" class="checkvideo" >
                            </div>
                        </div>
                    </li>
                <?php
                } ?>
            </ul>
        </div>
    <?php }else { 
        echo "1";
    }
    die();
}

/** 
* Function _fnWeyShowDiaglogContent()
* Used to display digalog content & allow wordpress function to use in ytp-dialog.php
* @return html 
*/
function _fnWeyShowDiaglogContent(){
  include_once(dirname(__FILE__)."/ytp-dialog.php");
  die();
}


/**
 * Gets a vimeo thumbnail url
 * @param mixed $id A vimeo id (ie. 1185346)
 * @return thumbnail's url
*/
function getVimeoThumb($id) {
    $data = file_get_contents("http://vimeo.com/api/v2/video/$id.json");
    $data = json_decode($data);
    return $data[0]->thumbnail_medium;
}
?>
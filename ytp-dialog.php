<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>YouTube/Vimeo Search</title>
	<script type="text/javascript" src="<?php echo includes_url(); ?>js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>js/jquery/jquery.js"></script>
  <?php
  require_once( dirname(__FILE__)."/lib/api_vimeo.php" );
  if(floatval(get_bloginfo("version")) > floatval("3.5.2"))
  {
    ?>
    <script type="text/javascript" src="<?php echo YTP_PLUGIN_URL; ?>/js/jquery-migrate.js"></script>
    <?php
  }
  ?>
  <script type="text/javascript" src="<?php echo YTP_PLUGIN_URL; ?>/js/dialog.js"></script>
  <script type="text/javascript" src="<?php echo YTP_PLUGIN_URL; ?>/js/bootstrap.js"></script>
  <link href="<?php echo YTP_PLUGIN_URL; ?>/css/bootstrap.css" rel="stylesheet">
  <link href="<?php echo YTP_PLUGIN_URL; ?>/css/weyCustomstyle.css" rel="stylesheet">

  <script type="text/javascript">
    jQuery(document).ready(function() {

      //tab js
      jQuery('.nav-tabs a').click(function (e) {
        e.preventDefault();
        jQuery(this).tab('show');
      })
      
      function _fnFetchVideoAjax(intPageNo) {

        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        var plug_url = "<?php echo YTP_PLUGIN_URL; ?>";
        
        var search =jQuery('.searchvalue').val();
        
        var offset =intPageNo;
        var data = {
            search : search,
            offset : offset,
            action : 'search_video_by_ajax'
          };
        jQuery('#youtube').html('<img src="'+plug_url+'/img/loading.gif" title="loading" style="margin:60px">'); 
        jQuery('#youtube').css("text-align","center");
        jQuery.ajax( {
          url:ajaxurl,
          data: data,
          success:function(data) {
            if(!jQuery('.well').hasClass('MarginBtmNone'))
            {
             jQuery('.well').addClass("MarginBtmNone");   
            }
            jQuery('#youtube').removeAttr("style");   
            jQuery('#youtube').html('');
            if (data == '1') {
              jQuery('#youtube').css("padding-top","20px");
              jQuery('#youtube').html("<div class='alert alert-error' ><button type='button' class='close' data-dismiss='alert'>&times;</button><strong>There are no videos.</strong> Try with different search</div>");
            }else{
              jQuery('#youtube').html(data);
            }
          }
        });
      }

      //ajax call for vimeo videos
      function _fnFetchVimeoVideoAjax(intPageNo) {

        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        var plug_url = "<?php echo YTP_PLUGIN_URL; ?>";

        var search =jQuery('.searchvimeovalue').val();
        var offset =intPageNo;
        var data = {
            search : search,
            offset : offset,
            action : 'search_vimeo_video_by_ajax'
          };
        jQuery('#vimeo').html('<img src="'+plug_url+'/img/loading.gif" title="loading" style="margin:60px">');
        jQuery('#vimeo').css("text-align","center");
        jQuery.ajax( {
          url:ajaxurl,
          data: data,
          success:function(data) {
            if(!jQuery('.well').hasClass('MarginBtmNone'))
            {
             jQuery('.well').addClass("MarginBtmNone");
            }
            jQuery('#vimeo').removeAttr("style");
            jQuery('#vimeo').html('');
            if (data == '1') {
              jQuery('#vimeo').css("padding-top","20px");
              jQuery('#vimeo').html("<div class='alert alert-error' ><button type='button' class='close' data-dismiss='alert'>&times;</button><strong>There are no videos.</strong> Try with different search</div>")
            }else{
              jQuery('#vimeo').html(data);
            }
          }
        });
      }

      jQuery('.search').live("click",function(e){

        e.preventDefault();
        var search =jQuery('.searchvalue').val();
        if (search == '') {
          jQuery('.searchvalue').css("border","1px solid red");
        }else{
          jQuery(".latestVideo").remove();
          _fnFetchVideoAjax();
        }
      });

      //call vimeo search
      jQuery('.searchVimeo').live("click",function(e){
        
        e.preventDefault();
        var search =jQuery('.searchvimeovalue').val();
        if (search == '') {
          jQuery('.searchvimeovalue').css("border","1px solid red");
        }else{
          jQuery(".latestVideo").remove();
          _fnFetchVimeoVideoAjax();
        }
      });

      jQuery("a.setDmn").live("click",function(e){
        e.preventDefault();
        jQuery(".dimenstion").fadeIn("slow");
        jQuery(this).hide();

      });
      jQuery('#youtubePagination li:not(.disabled) a').live("click",function(e){
        jQuery('#insert1').attr('disabled', 'disabled');
        jQuery('#insert1').addClass('disabled');
        e.preventDefault();
        if(!jQuery(this).parent().hasClass("disabled") || !jQuery(this).parent().hasClass("active"))
        {
          var page = jQuery(this).text();
          if(page == "Prev" || page == "Next" )
          {
            if(page == "Prev"){
                page = jQuery("#youtubePagination li.active a").text() -1;
            }
            else {
              page = parseInt(jQuery("#youtubePagination li.active a").text())+1;
            }
          }
          _fnFetchVideoAjax(parseInt(page));
        }
      });

      jQuery('#vimeoPagination li:not(.disabled) a').live("click",function(e){
        jQuery('#insert1').attr('disabled', 'disabled');
        jQuery('#insert1').addClass('disabled');
        e.preventDefault();
        if(!jQuery(this).parent().hasClass("disabled") || !jQuery(this).parent().hasClass("active"))
        {
          var page = jQuery(this).text();
          if(page == "Prev" || page == "Next" )
          {
            if(page == "Prev"){
                page = jQuery("#vimeoPagination li.active a").text() -1;
            }
            else {
              page = parseInt(jQuery("#vimeoPagination li.active a").text())+1;
            }
          }
          _fnFetchVimeoVideoAjax(parseInt(page));
        }
      });

      if ( jQuery('#shortcode').val() !='' ) {
        jQuery('#insert1').removeAttr('disabled');
      } else
      {
        jQuery('#insert1').attr('disabled','disabled');
        jQuery('#insert1').attr('class','btn disabled');
      }
    });
  </script>
</head>
<body>
    <div class="row">
        <div class="span8 maincont">
            <div class="well well-small alignCenter">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#youtubeArea" data-toggle="tab">Youtube</a></li>
                <li><a href="#vimeoArea" data-toggle="tab">Vimeo</a></li>
              </ul>
              <div class="row MarginZero">
                <form action="#" onsubmit="WEYInsertDialog.insert();return false;" action="#" id="gdedialog">
                  <div class="tab-content">
                    <div class="tab-pane active" id="youtubeArea">
                      <div class="input-append">
                        <input class="span4 searchvalue" placeholder="Enter Keyword to search video" id="appendedInputButton" value="" style="color:#aaa" onfocus="this.value='';this.style.color='#000';this.onfocus='';" type="text" maxlength="30">
                        <button class="btn search" type="button">Search</button>
                      </div>
                      <div id="youtube"></div>
                    </div>
                    <div class="tab-pane" id="vimeoArea">
                      <div class="input-append">
                        <input class="span4 searchvimeovalue" placeholder="Enter Keyword to search video" value="" style="color:#aaa" onfocus="this.value='';this.style.color='#000';this.onfocus='';" type="text" maxlength="30">
                        <button class="btn searchVimeo" type="button">Search</button>
                      </div>
                      <div id="vimeo"></div>
                    </div>
                  </div>
                  <textarea name="shortcode" id="shortcode" readonly="readonly" class="hide"></textarea>
                  <div class="mceActionPanel navbar navbar-fixed-bottom alignCenter">
                    <div class="navbar-inner">
                      <div class="span5 footerData">
                        <div class="dimenstion pull-left">
                            <input type="text" class="input-small" name="width" id="width" placeholder="Width (optional)" onkeypress="return isNumber(event)" value="" style="color:#aaa" onfocus="this.value='';this.style.color='#000';this.onfocus='';" maxlength="3">
                            <input type="text" class="input-small" name="height" id="height" value="" style="color:#aaa" onfocus="this.value='';this.style.color='#000';this.onfocus='';" placeholder="Height(optional)" onkeypress="return isNumber(event)" maxlength="3">
                        </div>
                        <input type="submit" name="submit" id="insert1" class="btn" value="Insert shortcode" >
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
        </div>
    </body>
</html>
//tinyMCEPopup.requireLangPack();
	
var WEYInsertDialog = {

	init : function() {
		var f = document.forms[0];
        var shortcode;var wt;var ht;
		jQuery('.checkvideo').live("click",function(e){
	        jQuery('.thumbnail').css('background','#fff');
	        jQuery('#insert1').removeAttr('disabled');
			jQuery('#insert1').removeAttr('class','disabled');
        	jQuery(this).parent().parent().css('background','#E6E6E6');
        	jQuery('#insert1').attr('class','btn');
	    });
		jQuery('#insert1').live("click", function(e){
			update_sc();
		});
		function update_sc() {
			var vimeoValue = jQuery('.vimeoThumbnails .checkvideo:checked').val();
			if( (jQuery(".searchOption").val() == "vimeo") && ( vimeoValue !== undefined) )
			{
				shortcode = 'https://vimeo.com/';
			}
			else
			{
				shortcode = 'http://www.youtube.com/watch?v=';
			}				

			if ( jQuery('.checkvideo:checked').val() !='' ) {
				shortcode = shortcode +jQuery('.checkvideo:checked').val();
			} 	
			if (jQuery('#width').val() !='' && isNaN(jQuery('#width').val()) == false) {
				wt = ' width="'+jQuery('#width').val()+'"';
			}else{
				wt='';
			}
			if (jQuery('#height').val() !='' && isNaN(jQuery('#height').val()) == false) {
				ht = ' height="'+jQuery('#height').val()+'"';
			}else{
				ht='';
			}
			var newsc = '[embed' +wt+ht+']'+shortcode+'[/embed]';
			jQuery('#shortcode').val(newsc);
		}
	},
	insert : function() {
		// insert the contents from the input into the document
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, jQuery('#shortcode').val());
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(WEYInsertDialog.init, WEYInsertDialog);

// function to check height and width is number or not
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
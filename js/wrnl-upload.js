jQuery(document).ready(function() {  
    jQuery('#upload_logo_button').click(function() {  
        tb_show('Upload a logo', 'media-upload.php?referer=wrnl_wp_unbranded&type=image&TB_iframe=true&post_id=0', false);  
        return false;  
    });  
    window.send_to_editor = function(html) {
		var image_url = jQuery('img',html).attr('src');
		jQuery('#logo_url_text').val(image_url);
		jQuery('#logo_url_img').attr('src', image_url);
		tb_remove();
	}

}); 
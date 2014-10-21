function isValidEmailAddress(emailAddress) {
	var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
	return pattern.test(emailAddress);
}

jQuery(document).ready(function($) {
	$('#contactbutton').click(function() {
		$('#contact-msg').html('<img src="' + ajax_object_acf.plugin_base_path + 'loading.gif" alt="' + ajax_object_acf.js_alt_loading + '">');
		$.ajax({
			type: 'POST',
			url: ajax_object_acf.ajax_url,
			data: $('#contactform').serialize(),
			dataType: 'json',
			beforeSend: function() {
				var message = $('#message').val();
				var name = $('#name').val();
				var email = $('#email').val();
				if (!message || !name || !email) {
					$('#contact-msg').html(ajax_object_acf.js_msg_empty_fields);
					return false;
				}
				if (!isValidEmailAddress(email)) {
					$('#contact-msg').html(ajax_object_acf.js_msg_invalid_email);
					return false;
				}
			},
			success: function(response) {
				if (response.status == 'success') {
					$('#contactform')[0].reset();
					if (ajax_object_acf.googleanalytics) {
						_gaq.push(['_trackPageview', ajax_object_acf.googleanalytics]);
					}
					if (ajax_object_acf.clickyanalytics) {
						clicky.goal( ajax_object_acf.clickyanalytics );
						clicky.pause( 500 );
					}
				}
				$('#contact-msg').html(response.errmessage);
			}
		});
	});
});

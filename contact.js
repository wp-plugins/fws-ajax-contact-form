

jQuery(document).ready(function($) {
	$('#contactbutton').click(function() {
		$('#contact-msg').html('<img src="' + ajax_object.plugin_base_path + 'loading.gif" alt="' + ajax_object.js_alt_loading + '">');
		var message = $('#message').val();
		var name = $('#name').val();
		var email = $('#email').val();
		if (!message || !name || !email) {
			$('#contact-msg').html(ajax_object.js_msg_empty_fields);
			return false;
		} else {
			$.ajax({
				type: "GET",
				url: 'https://api.mailgun.net/v2/address/validate?callback=?',
				data: { address: email, api_key: ajax_object.mailgun_key },
				dataType: "jsonp",
				crossDomain: true,
				success: function(data, status_text) {
					if (data['is_valid']) {
						$.ajax({
							type: 'POST',
							url: ajax_object.ajax_url,
							data: $('#contactform').serialize(),
							dataType: 'json',
							success: function(response) {
								if (response.status == 'success') {
									$('#contactform')[0].reset();
								}
								$('#contact-msg').html(response.errmessage);
							}
						});
					} else {
						if (data['did_you_mean']) {
							$('#contact-msg').html( ajax_object.js_msg_did_you_mean + ' <em>' +  data['did_you_mean'] + '</em>?');
						} else {
							$('#contact-msg').html(ajax_object.js_msg_invalid_email);
						}
						return false;
					}
				},
				error: function(request, status_text, error) {
					$('#contact-msg').html(ajax_object.js_msg_error_validation);
					return false;
				}
			});
		}
	});
});

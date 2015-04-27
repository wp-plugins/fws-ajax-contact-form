<?php
/*
Plugin Name: Ajax Contact Form
Version: 1.0.5
Plugin URI: http://www.finalwebsites.com/ajax-contact-form-wordpress/
Description: An easy to use Ajax contact form with (optional) advanced email address validation provided by Mailgun.
Author: Olaf Lederer
Author URI: http://www.finalwebsites.com/
Text Domain: fws-ajax-contact-form
Domain Path: /languages/
License: GPL v3

Ajax Contact Form
Copyright (C) 2015, Olaf Lederer - http://www.finalwebsites.com/contact/

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if ( is_admin() ) {
	register_deactivation_hook(__FILE__, 'fwsacf_deactivate');
}
add_action( 'plugins_loaded', 'FWSACF_load_textdomain' );

function FWSACF_load_textdomain() {
	load_plugin_textdomain( 'fws-ajax-contact-form', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action('admin_menu', 'FWSACF_plugin_menu');

function FWSACF_plugin_menu() {
	add_options_page('Ajax Contact Form Options', 'Ajax Contact Form', 'manage_options', 'FWSACF-topmenu', 'FWSACF_options_page');
	add_action( 'admin_init', 'register_FWSACF_setting' );
}

function register_FWSACF_setting() {
	register_setting( 'FWSACF_options', 'fwsacf-mailto' );
	register_setting( 'FWSACF_options', 'fwsacf-emailfrom' );
	register_setting( 'FWSACF_options', 'fwsacf-emailsubject', 'strip_tags' );
	register_setting( 'FWSACF_options', 'fwsacf-thankyoumessage' );
	register_setting( 'FWSACF_options', 'fwsacf-apiKey' );
	register_setting( 'FWSACF_options', 'fwsacf-include-css' );
	register_setting( 'FWSACF_options', 'fwsacf-googleanalytics' );
	register_setting( 'FWSACF_options', 'fwsacf-clickyanalytics' );
}

function FWSACF_options_page() {

	echo '
	<div class="wrap">
		<h2>Ajax Contact Form by finalwebsites</h2>
		<p>'.sprintf ( __( 'Configure your contact form options below. If you like to change the (error) messages and/or labels visible via the web form, please use the plugin (f.e. <a href="%s" target="_blank">%s</a>).', 'fws-mailchimp-subscribe' ), esc_url( 'https://wordpress.org/plugins/loco-translate/' ), 'Loco Translate' ).'</p>';
	if (!get_option('fwsacf-apiKey')) echo '
		<p>'.sprintf ( __( 'Enter a valid Mailgun API key to use the advanced email address validation feature. Register a free <a href="%s">Mailgun account</a> to get your API key.', 'fws-mailchimp-subscribe' ), esc_url( 'https://mailgun.com/signup' ) ).'</p>';

	echo '
		<form action="options.php" method="post">';
	settings_fields( 'FWSACF_options' );

	echo '
			<h3>'.__( 'Configuration', 'fws-ajax-contact-form' ).'</h3>

			<table class="form-table">
				<tr valign="top">
					<th scope="row">'.__( ' Email address (to): ', 'fws-ajax-contact-form' ).'</th>
					<td>
						<input class="regular-text" type="text" placeholder="" value="'.esc_attr( get_option('fwsacf-mailto', get_option( 'admin_email' )) ).'" name="fwsacf-mailto">
						<p class="description">'.__( 'The email address where you like to receive the messages.', 'fws-ajax-contact-form' ).'</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">'.__( ' Email address (from): ', 'fws-ajax-contact-form' ).'</th>
					<td>
						<input class="regular-text" type="text" placeholder="" value="'.esc_attr( get_option('fwsacf-emailfrom') ).'" name="fwsacf-emailfrom">
						<p class="description">'.__( 'The email address which is the sender, f.e. mail@yoursite.com.', 'fws-ajax-contact-form' ).'</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">'.__( ' Email subject(s): ', 'fws-ajax-contact-form' ).'</th>
					<td>
						<textarea class="large-text" rows="3" name="fwsacf-emailsubject">'.esc_textarea( get_option('fwsacf-emailsubject', __( 'A message from your website\'s contact form' , 'fws-ajax-contact-form' )) ).'</textarea>
						<p class="description">'.__( 'The email subject for each message sent by the contact form. Add multiple subject rows to create a SELECT menu for your contact form.', 'fws-ajax-contact-form' ).'</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">'.__( 'Thank you - message: ', 'fws-ajax-contact-form' ).'</th>
					<td>
						<textarea class="large-text" rows="2" name="fwsacf-thankyoumessage">'.esc_textarea( get_option('fwsacf-thankyoumessage', __( 'Thanks, for your message. We will respond as soon as possible.' , 'fws-ajax-contact-form' )) ).'</textarea>
						<p class="description">'.__( 'Add here your personal "Thank You" message which appears after the contact form was submitted (some HTML allowed).', 'fws-ajax-contact-form' ).'</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">'.__( ' API Key ', 'fws-ajax-contact-form' ).'</th>
					<td>
						<input class="regular-text" type="text" placeholder="" value="'.esc_attr( get_option('fwsacf-apiKey') ).'" name="fwsacf-apiKey">
						<p class="description">'.__( 'Your Mailgun Public API key, which starts "pubkey-". Keep this field empty to disable that feature.', 'fws-ajax-contact-form' ).'</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">'.__( ' Include CSS ', 'fws-ajax-contact-form' ).'</th>
					<td>
						<label for="fwsacf-include-css">
						<input id="fwsacf-include-css" type="checkbox" value="1" name="fwsacf-include-css" '.checked( get_option('fwsacf-include-css'), 1, false ).'>
						'.__( 'Include our stylesheet for your contact form', 'fws-ajax-contact-form' ).'
						</label>
					</td>
				</tr>
			</table>
			<h4>'.__( 'Don\'t use the fields below if your Google Analytics or Clicky JavaScript snippet isn\'t installed!', 'fws-ajax-contact-form' ).'</h4>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">'.__( ' Track page views in Google Analytics ', 'fws-ajax-contact-form' ).'</th>
					<td>
						<input class="regular-text" type="text" placeholder="" value="'.esc_attr( get_option('fwsacf-googleanalytics') ).'" name="fwsacf-googleanalytics">
						<p class="description">'.__( 'Track a page view in Google analytics after the form is submitted (f.e. /contact/submitted.html).', 'fws-ajax-contact-form' ).'</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">'.__( ' Track goals in Clicky ', 'fws-ajax-contact-form' ).'</th>
					<td>
						<input class="regular-text" type="text" placeholder="" value="'.esc_attr( get_option('fwsacf-clickyanalytics') ).'" name="fwsacf-clickyanalytics">
						<p class="description">'.__( 'Add here the goal ID for a manual goal you\'ve already defined in Clicky (check the FAQ for information).', 'fws-ajax-contact-form' ).'</p>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input class="button-primary" type="submit" value="'.__( 'Save Changes', 'fws-ajax-contact-form' ).'">
			</p>
		</form>
		<h3>'.__( 'How to use?', 'fws-ajax-contact-form' ).'</h3>
		<p>'.__( 'Add the following shortcode into your page. Use the shortcode attribute "emailsubject" to overwrite the setting for the mail subject from this page. Enter multiple subject values, divided by "|" (pipe) symbols, to create a SELECT menu.', 'fws-ajax-contact-form' ).'</p>
		<p><code>[FWSAjaxContactForm]</code> &nbsp; <code>[FWSAjaxContactForm emailsubject="My email subject"]</code></p>
	</div>';
}


function FWS_contactform_add_script() {
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'FWSAjaxContactForm') ) {
		wp_enqueue_script( 'fws-contactform-script', plugin_dir_url(__FILE__).'contact.js', array('jquery') );
		wp_localize_script( 'fws-contactform-script', 'ajax_object_acf',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'plugin_base_path' => plugin_dir_url(__FILE__),
				'googleanalytics' => get_option('fwsacf-googleanalytics'),
				'clickyanalytics' => get_option('fwsacf-clickyanalytics'),
				'js_alt_loading' => __( 'Loading...', 'fws-ajax-contact-form' ),
				'js_msg_empty_fields' => __( 'At least one of the form fields is empty.', 'fws-ajax-contact-form' ),
				'js_msg_invalid_email' => __( 'The entered email address isn\'t valid.', 'fws-ajax-contact-form' )
			)
		);
		if (get_option('fwsacf-include-css')) {
			wp_enqueue_style( 'fws-contactform-style', plugin_dir_url(__FILE__).'style.css' );
		}
	}
}
add_action('wp_enqueue_scripts', 'FWS_contactform_add_script');


function FWS_createAjaxContactForm($atts = null) {
	$atts = shortcode_atts(
		array(
			'emailsubject' => get_option('fwsacf-emailsubject')
		),
		$atts
	);
	$html = '
	<form id="contactform" role="form">
		<div class="form-group">
			<label for="name">'.__( 'Name' , 'fws-ajax-contact-form' ).'</label>
			<input class="form-control input-sm" type="text" name="name" id="name" size="30" tabindex="1" />
		</div>
		<div class="form-group">
			<label for="email">'.__( 'Email address' , 'fws-ajax-contact-form' ).'</label>
			<input class="form-control input-sm" type="email" name="email" id="email" size="30" tabindex="2" />
		</div>';
		if ($atts['emailsubject'] != '') {
			$subj_parts = preg_split('/[\r\n\|]+/', $atts['emailsubject']);
			if (count($subj_parts) > 1) {
				$html .= '
		<div class="form-group">
			<label for="message">'.__( 'Subject' , 'fws-ajax-contact-form' ).'</label>
			<select class="form-control" name="mailsubject">
				<option value="">Choose one...</option>';
				foreach ($subj_parts as $part) {
					$html .= '
				<option value="'.$part.'">'.$part.'</option>';
				}
				$html .= '
			</select>
		</div>';
			} else {
				$html .= '
		<input type="hidden" name="mailsubject" value="'.esc_attr($atts['emailsubject']).'" />';
			}
		}
		$html .= '
		<div class="form-group">
			<label for="message">'.__( 'Message' , 'fws-ajax-contact-form' ).'</label>
			<textarea class="form-control" name="message" id="message" tabindex="3" rows="8"></textarea>
		</div>
		<div class="form-group text-right">
			<input type="hidden" name="action" value="contactform_action" />
			'.wp_nonce_field('ajax_contactform', '_acf_nonce', true, false).'
			<button class="btn btn-primary btn-sm" id="contactbutton" type="button">'.__( 'Submit' , 'fws-ajax-contact-form' ).'</button>
		</div>
	</form>
	<div id="contact-msg" class="error-message"></div>';
	return $html;
}
add_shortcode('FWSAjaxContactForm', 'FWS_createAjaxContactForm');


function check_email_address_mailgun($email) {
	if ($key = get_option('fwsacf-apiKey')) {
		$url = 'https://api.mailgun.net/v2/address/validate?address='.urlencode($email);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERPWD, 'api:'.$key);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		//print_r($response);
		$obj = json_decode($response);
		if ($obj->is_valid) {
			return 1;
		} else {
			if ($obj->did_you_mean) {
				return sprintf(__( 'Invalid email address, did you mean "%s"', 'fws-ajax-contact-form' ), $obj->did_you_mean);
			} else {
				return __( 'The entered email address isn\'t valid.', 'fws-ajax-contact-form' );
			}
		}
	} else {
		if (is_email($email)) {
			return 1;
		} else {
			return __( 'The entered email address isn\'t valid.', 'fws-ajax-contact-form' );
		}
	}
}


function FWS_ajax_contactform_action_callback() {
	$error = '';
	$status = 'error';
	if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
		$error = __( 'All fields are required to enter.' , 'fws-ajax-contact-form' );
	} else {
		if (!wp_verify_nonce($_POST['_acf_nonce'], 'ajax_contactform')) {
			$error = __( 'Verification error, try again.' , 'fws-ajax-contact-form' );
		} else {
			$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
			$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
			$email_check = check_email_address_mailgun($email);
			if ($email_check == 1) {
				if (!empty($_POST['mailsubject'])) {
					$subject = filter_var($_POST['mailsubject'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
				} else {
					$subject = __( 'A message from your website\'s contact form' , 'fws-ajax-contact-form' );
					if ($subject_option = get_option('fwsacf-emailsubject')) {
						$subj_parts = explode(PHP_EOL, $subject_option);
						if (count($subj_parts) == 1) $subject = $subject_option;
					}
				}
				$message = wp_kses(stripcslashes($_POST['message']), array()); 
				$message .= sprintf(__( '%1$sIP address: %2$s' , 'fws-ajax-contact-form' ), PHP_EOL.PHP_EOL, $_SERVER['REMOTE_ADDR']);
				$message .= sprintf(__( '%1$sSender\'s name: %2$s' , 'fws-ajax-contact-form' ), PHP_EOL, $name);
				$message .= sprintf(__( '%1$sE-mail address: %2$s' , 'fws-ajax-contact-form' ), PHP_EOL, $email);
				$to = get_option('fwsacf-mailto', get_option('admin_email'));
				$sitename = strtolower( $_SERVER['SERVER_NAME'] );
				if ( substr( $sitename, 0, 4 ) == 'www.' ) {
					$sitename = substr( $sitename, 4 );
				}
				$emailfrom = get_option('fwsacf-emailfrom', 'noreply@'.$sitename);
				$header = 'From: '.get_option('blogname').' <'.$emailfrom.'>'.PHP_EOL;
				$header .= 'Reply-To: '.$email.PHP_EOL;
				if ( wp_mail($to, $subject, $message, $header) ) {
					$status = 'success';
					$thankyou = get_option('fwsacf-thankyoumessage');
					$error = ($thankyou != '') ? $thankyou : __( 'Thanks, for your message. We will respond as soon as possible.' , 'fws-ajax-contact-form' );
				} else {
					$error = __( 'The script can\'t send this email message.' , 'fws-ajax-contact-form' );
				}
			} else {
				$error = $email_check;
			}
		}
	}

	$resp = array('status' => $status, 'errmessage' => $error);
	wp_send_json($resp);
}
add_action( 'wp_ajax_contactform_action', 'FWS_ajax_contactform_action_callback' );
add_action( 'wp_ajax_nopriv_contactform_action', 'FWS_ajax_contactform_action_callback' );

function fwsacf_deactivate() {
	// nothing to do
}

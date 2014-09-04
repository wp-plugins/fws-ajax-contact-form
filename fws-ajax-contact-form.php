<?php
/*
Plugin Name: Ajax Contact Form
Version: 1.0.2
Plugin URI: http://www.finalwebsites.com/ajax-contact-form-wordpress/
Description: Creates an Ajax contact form with email address validation using the Mailgun API system.
Author: Olaf Lederer
Author URI: http://www.finalwebsites.com/
Text Domain: fws-ajax-contact-form
Domain Path: /languages/
License: GPL v3

Ajax Contact Form
Copyright (C) 2014, Olaf Lederer - olaf@finalwebsites.com

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
	register_setting( 'FWSACF_options', 'fwsacf-emailsubject' );
	register_setting( 'FWSACF_options', 'fwsacf-apiKey' );
	register_setting( 'FWSACF_options', 'fwsacf-include-css' );
	register_setting( 'FWSACF_options', 'fwsacf-googleanalytics' );
	register_setting( 'FWSACF_options', 'fwsacf-clickyanalytics' );
}

function FWSACF_options_page() {

	echo '
	<div class="wrap">
		<h2>Ajax Contact Form by finalwebsites</h2>
		<p>Configure your contact form options below. If you like to change the (error) messages and/or labels visible via the web form, please use a localization plugin (f.e. <a href="https://wordpress.org/plugins/codestyling-localization/" target="_blank">Codestyling Localization</a>).</p>
		<p>You need a valid API key for the email address validation feature. Register a free <a href="https://mailgun.com/signup">Mailgun account</a> to get your API key.</p>';

	echo '
		<form action="options.php" method="post">';
	settings_fields( 'FWSACF_options' );
	echo '
			<h3>Configuration</h3>

			<table class="form-table">
				<tr valign="top">
					<th scope="row"> Email address (to): </th>
					<td>
						<input class="regular-text" type="text" placeholder="" value="'.esc_attr( get_option('fwsacf-mailto') ).'" name="fwsacf-mailto">
						<p class="description">The email address where you like to receive the messages.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"> Email address (from): </th>
					<td>
						<input class="regular-text" type="text" placeholder="" value="'.esc_attr( get_option('fwsacf-emailfrom') ).'" name="fwsacf-emailfrom">
						<p class="description">The email address which is the sender, f.e. mail@yoursite.com.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"> Email subject: </th>
					<td>
						<input class="regular-text" type="text" placeholder="" value="'.esc_attr( get_option('fwsacf-emailsubject') ).'" name="fwsacf-emailsubject">
						<p class="description">The email subject for each message sent by the contact form.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"> API Key </th>
					<td>
						<input class="regular-text" type="text" placeholder="" value="'.esc_attr( get_option('fwsacf-apiKey') ).'" name="fwsacf-apiKey">
						<p class="description">Your Mailgun Public API key, which starts "pubkey-".</p>
					</td>
				</tr>';
				$checked = (get_option('fwsacf-include-css')) ? ' checked="checked"' : '';
				echo '
				<tr valign="top">
					<th scope="row"> Include CSS </th>
					<td>
						<label for="fwsacf-include-css">
						<input id="fwsacf-include-css" type="checkbox" value="1" name="fwsacf-include-css"'.$checked.'>
						Include our stylesheet for your web form
						</label>
					</td>
				</tr>
			</table>
			<h4>Don\'t use the fields below if your Google Analytics or Clicky JavaScript snippet isn\'t installed!</h4>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"> Track page views in Google Analytics </th>
					<td>
						<input class="regular-text" type="text" placeholder="" value="'.esc_attr( get_option('fwsacf-googleanalytics') ).'" name="fwsacf-googleanalytics">
						<p class="description">Track a page view in Google analytics after the form is submitted (f.e. /contact/submitted.html).</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"> Track goals in Clicky </th>
					<td>
						<input class="regular-text" type="text" placeholder="" value="'.esc_attr( get_option('fwsacf-clickyanalytics') ).'" name="fwsacf-clickyanalytics">
						<p class="description">Add here the goal ID for a manual goal you\'ve already defined in Clicky (check the FAQ for information).</p>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input class="button-primary" type="submit" value="Save Changes">
			</p>
		</form>';
		if (get_option('fwsacf-apiKey')) echo '
		<h3>How to use?</h3>
		<p>Add the following shortcode into your page. Use the shortcode attribute "emailsubject" to overwrite the setting for the mail subject from this page.</p>
		<p><code>[FWSAjaxContactForm]</code> or <code>[FWSAjaxContactForm emailsubject="My email subject"]</code></p>
	</div>';
}


function FWS_contactform_add_script() {
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'FWSAjaxContactForm') ) {
		wp_enqueue_script( 'fws-contactform-script', plugin_dir_url(__FILE__).'contact.js', array('jquery') );
		wp_localize_script( 'fws-contactform-script', 'ajax_object',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'mailgun_key' => get_option('fwsacf-apiKey'),
				'plugin_base_path' => plugin_dir_url(__FILE__),
				'googleanalytics' => get_option('fwsacf-googleanalytics'),
				'clickyanalytics' => get_option('fwsacf-clickyanalytics'),
				'js_alt_loading' => __( 'Loading...', 'fws-ajax-contact-form' ),
				'js_msg_empty_fields' => __( 'At least one of the form fields is empty.', 'fws-ajax-contact-form' ),
				'js_msg_did_you_mean' => __( 'Error, did you mean', 'fws-ajax-contact-form' ),
				'js_msg_invalid_email' => __( 'The entered mail address is invalid.', 'fws-ajax-contact-form' ),
				'js_msg_error_validation' => __( 'Error occurred, unable to validate your email address.', 'fws-ajax-contact-form' )
			)
		);
		if (get_option('fwsacf-include-css')) {
			wp_enqueue_style( 'fws-contactform-style', plugin_dir_url(__FILE__).'style.css' );
		}
	}
}
add_action('wp_enqueue_scripts', 'FWS_contactform_add_script');




function FWS_createAjaxContactForm($atts) {
	extract( shortcode_atts(
		array(
			'emailsubject' => ''
		), $atts )
	);
	if (!get_option('fwsacf-apiKey')) {
		return '<p>Please enter the Mailgun API key!</p>';
	} else {
		$html = '
	<form id="contactform" role="form">
		<div class="form-group">
			<label for="name">'.__( 'Name' , 'fws-ajax-contact-form' ).'</label>
			<input class="form-control input-sm" type="text" name="name" id="name" size="30" tabindex="1" />
		</div>
		<div class="form-group">
			<label for="email">'.__( 'Email address' , 'fws-ajax-contact-form' ).'</label>
			<input class="form-control input-sm" type="email" name="email" id="email" size="30" tabindex="2" />
		</div>
		<div class="form-group">
			<label for="message">'.__( 'Message' , 'fws-ajax-contact-form' ).'</label>
			<textarea class="form-control" name="message" id="message" tabindex="3" rows="8"></textarea>
		</div>
		<div class="form-group text-right">';
		if ($emailsubject != '') $html .= '
			<input type="hidden" name="mailsubject" value="'.esc_attr($emailsubject).'" />';
		$html .= '
			<input type="hidden" name="action" value="contactform_action" />
			'.wp_nonce_field('ajax_contactform', '_acf_nonce', true, false).'
			<button class="btn btn-primary btn-sm" id="contactbutton" type="button">'.__( 'Submit' , 'fws-ajax-contact-form' ).'</button>
		</div>
	</form>
	<div id="contact-msg" class="error-message"></div>';
		return $html;
	}
}
add_shortcode('FWSAjaxContactForm', 'FWS_createAjaxContactForm');


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
			if (!empty($_POST['mailsubject'])) {
				$subject = filter_var($_POST['mailsubject'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
			} else {
				$subject = (get_option('fwsacf-emailsubject')) ? get_option('fwsacf-emailsubject') : __( 'A message from your website\'s contact form' , 'fws-ajax-contact-form' );
			}
			$message = esc_attr($_POST['message']);
			$message .= sprintf(__( '%1$sIP address: %2$s' , 'fws-ajax-contact-form' ), PHP_EOL.PHP_EOL, $_SERVER['REMOTE_ADDR']);
			$message .= sprintf(__( '%1$sSender\'s name: %2$s' , 'fws-ajax-contact-form' ), PHP_EOL, $name);
			$message .= sprintf(__( '%1$sE-mail address: %2$s' , 'fws-ajax-contact-form' ), PHP_EOL, $email);
			$sendmsg = __( 'Thanks, for the message. We will respond as soon as possible.' , 'fws-ajax-contact-form' );
			$to = (get_option('acf-mailto')) ? get_option('acf-mailto') : get_option('admin_email');
			$sitename = strtolower( $_SERVER['SERVER_NAME'] );
			if ( substr( $sitename, 0, 4 ) == 'www.' ) {
				$sitename = substr( $sitename, 4 );
			}
			$emailfrom = (get_option('acf-emailfrom')) ? get_option('acf-emailfrom') : 'noreply@'.$sitename;
			$header = 'From: '.get_option('blogname').' <'.$emailfrom.'>'.PHP_EOL;
			$header .= 'Reply-To: '.$email.PHP_EOL;
			if ( wp_mail($to, $subject, $message, $header) ) {
				$status = 'success';
				$error = $sendmsg;
			} else {
				$error = __( 'Some errors occurred.' , 'fws-ajax-contact-form' );
			}
		}
	}

	$resp = array('status' => $status, 'errmessage' => $error);
	wp_send_json($resp);
}
add_action( 'wp_ajax_contactform_action', 'FWS_ajax_contactform_action_callback' );
add_action( 'wp_ajax_nopriv_contactform_action', 'FWS_ajax_contactform_action_callback' );


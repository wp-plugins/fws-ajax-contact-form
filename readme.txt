=== Ajax Contact Form ===
Contributors: finalwebsites
Donate link: http://www.finalwebsites.com/
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Tags: contact form, ajax contact form, email, contact, forms, api, ajax, email form, shortcode, clicky, Google Analytics, tracking
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 1.0.3

An easy to use Ajax contact form with (optional) advanced email address validation provided by Mailgun.

== Description ==

This contact form plugin is used to create a simple contact form using Ajax technology and advanced email address validation. In difference to most other plugins, there is no form-builder included.

= Check the features: =

* Advanced email address validation using the Mailgun API system
* Using nonces for simple form value validation
* Works with the default wp_mail() function (use it together with the [Mailgun for Wordpress](https://wordpress.org/plugins/mailgun/) plugin to send emails via SMTP)
* Options for the email subject and the from/to email addresses
* You can change/translate all public text messages while using a localization tool
* The form HTML is compatibel with the Boostrap CSS framework
* Optional: use the CSS style-sheet included by the plugin
* Track succesfully submitted forms in Google Analytics and/or Clicky

The plugin is built te keep stuff simple. If you need a complex web form or if you need a form builder, please use one of the existing form plugins. To use the email validation feature you need an API key. Open a [free Mailgun account](https://mailgun.com/signup) to get one.


== Installation ==

The quickest method for installing the contact form is:

1. Automatically install using the builtin WordPress Plugin installer or...
1. Upload the entire `fws-ajax-contact-form` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Add the shortcode [FWSAjaxContactForm] into the page of your choice.

== Frequently Asked Questions ==

= Is it possible to use a differnet method for email address validation (instead of using the Mailgun API) =

Yes, the external email address validation service is optional. My advice is to use them, because it's very important to receive an email address that is correct. The Mailgun API system will test an email address for:

* Syntax checks (RFC defined grammar)
* DNS validation
* Spell checks
* Email Service Provider (ESP) specific local-part grammar (if available).

= How to add a manual goal in Clicky? =

If you use a Clicky premium plan  it's possible to track Goals.

1. In Clicky click on Goals > Setup > Create a new goal.
1. Enter a name for the goal
1. Check the "Manual Goal" checkbox and click Submit
1. Copy/paste the ID into the field from the plugin options page

== Screenshots ==
1. An example how the form looks like.
2. Settings for the *Ajax Contact form*.


== Changelog ==

= 1.0.3 =

* Other
	* Added updated screenshots

* Bugfixes
	* The object name used for wp_localize_script is changed because of possible conflicts with other plugins or themes

* Enhancement
	* The Mailgun email address validation feature is now optional. The validation process is also moved from client side code (JS) to the server side code (PHP).
	* Before the form gets submitted, a simple email address validation (regular expression) is done.
	* Now it's possible to enter multiple subjects for the email message. These subjects are used to create a SELECT menu for the contact form
	* It's possible now to translate the complete plugin


= 1.0.2.1 =

* Other
	* Added icons for the plugin repository
	* The plugin is tested for WordPress 4.0
	* Added instructions when using the track goal feature
	* Added an updated screenshot for the plugin settings

= 1.0.2 =

* Bugfixes
	* Removed some typos in the text

* Enhancement
	* Simple goal or conversion tracking for Google Analytics and Clicky

= 1.0.1 =

* Bugfixes
	* Fixed the name for the nonce verification
	* Removed the bug for the wrong addressing of the ajax call back function

* Enhancement
	* Added some screenshots and FAQ's
	* Overwrite the mail subject global setting by using a shortcode attribute

= 1.0 =
* Initial release


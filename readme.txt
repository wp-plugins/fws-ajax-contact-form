=== FWS Ajax Contact Form ===
Contributors: finalwebsites
Tags: email, contact, forms, api
Requires at least: 3.0
Tested up to: 3.9
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates an Ajax contact form with email address validation powered by Mailgun.

== Description ==

This contact form plugin is used to create a simple contact form using Ajax technology and email address validation. In difference to most other plugins, there is no form-builder included.

Check the features:

* Email address validation using the Mailgun API system
* Using nonces for simple form value validation
* Works with the default wp_mail() function (use it together with the Mailgun for Wordpress plugin to send emails via SMTP)
* Options for the email subject and the from/to email addresses
* You can change/translate all public text messages while using a localization tool
* The form HTML is compatibel with the Boostrap CSS framework
* Optional: use the plugin style-sheet

The plugin is build te keep stuff simple. If you need a complex web form or if you need a form builder, please use one of the existing form plugins. To use the email validation feature you need an API key. Open a [free Mailgun account](https://mailgun.com/signup) to get one.

== Installation ==

The quickest method for installing the contact form is:

1. Automatically install using the builtin WordPress Plugin installer or...
1. Upload entire `fws-ajax-contact-form` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Enter the Mailgun API key and the other options from the plugin settings page
1. Add the shortcode [FWSAjaxContactForm] into the page of your choice

== Frequently Asked Questions ==

== Changelog ==

= 1.0 =
* Initial release

== Screenshots ==

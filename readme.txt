=== netFORUM Single Sign On  ===
Contributors: fsahsen
Tags: Netforum, sso, SingleSign-on
Requires at least: 3.0.1
Tested up to: 6.4
Stable tag: 1.3.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allow users to log into your WordPress site using netFORUM Pro eWeb credentials.

== Description ==
netFORUM Single Sign-On by fusionSpan allows users to sign into WordPress using their netFORUM eWeb credentials.  This plugin utilizes netFORUM’s xWeb Web Service API to authenticate users.

This plugin requires xWeb, which is available to all netFORUM Team and Pro subscriptions.

**Additional Features**

fusionSpan specializes in building commercial WordPress plugins that enable integrations between your netFORUM database and your WordPress website. <a href="https://www.fusionspan.com/contact-us/" target="_blank">Contact us</a> for these additional integrations:

* **Group-based web access**.  Restrict specific site content to members or specific member groups.  Create WordPress User Role groups based on netFORUM fields.

* **Single sign-out**.  Logout of netFORUM eWeb on WordPress log out.

**Website**

<a href="https://wordpress.org/plugins/search/fusionspan/" target="_blank">Check out some of our other plugins</a>, or visit our website <a href="https://www.fusionspan.com/" targer="_blank">https://www.fusionspan.com/</a> to learn more about our products and services.

For questions about this plugin, please fill out our <a href="https://www.fusionspan.com/contact-us/" target="_blank">Contact Us form</a> or email us at [help@fusionspan.com](mailto:help+web@fusionspan.com). 

== Installation ==

netFORUM Pro Single Sign-On (SSO) plugin uses the netFORUM xWeb services to authenticate users. This allows for SSO Capabilities, where users can sign in to WordPress using their netFORUM username and password.

1. Unzip and upload the folder contents of **netFORUM_sso.zip** in to the ''/wp-content/plugins/'' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go into the WordPress Admin Dashboard and click on ‘fusionSpan’ in the left sidebar.
4. Configure the following fields on the page to connect your WordPress website with your netFORUM Pro instance:
		a. **xWeb Single SignOn WSDL URL** – The URL for the netFORUM xWeb SSO web service (usually of the form <a href="https://netforumpro.com/xweb/signon.asmx?wsdl">https://netforumpro.com/xweb/signon.asmx?wsdl</a> for your live instance or <a href="https://uat.netforumpro.com/xweb/signon.asmx?wsdl">https://uat.netforumpro.com/xweb/signon.asmx?wsdl</a> for your test instance)
		b. **xWeb Username** – The xWeb Service  username
		c. **xWeb Password** – The  xWeb Service password
5.  Check license.txt file for the license key required to activate the plugin.
6.  We have provided the details to obtain the License key in our **readme file**. After downloading or updating the plugin, it is mentioned in point #5 under the installation section in the readme file about how and where to obtain the license key.
We have provided a **license.txt** file in the **root** location of the **plugin folder** which has the license key.		

== Frequently Asked Questions ==
netFORUM Pro Single Sign-On (SSO) plugin uses the netFORUM xWeb services to authenticate users. This allows for SSO Capabilities, where users can sign in to WordPress using their netFORUM username and password.

= Installation Instructions =

1. Unzip and upload the folder contents of **netFORUM_sso.zip** in to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go into the WordPress Admin Dashboard and click on ‘fusionSpan’ in the left sidebar.
4. Configure the following fields on the page to connect your WordPress website with your netFORUM Pro instance:
		a. **xWeb Single SignOn WSDL URL** – The URL for the netFORUM xWeb SSO web service (usually of the form <a href="https://netforumpro.com/xweb/signon.asmx?wsdl">https://netforumpro.com/xweb/signon.asmx?wsdl</a> for your live instance or <a href="https://uat.netforumpro.com/xweb/signon.asmx?wsdl">https://uat.netforumpro.com/xweb/signon.asmx?wsdl</a> for your test instance)
		b. **xWeb Username** – The xWeb Service  username
		c. **xWeb Password** – The  xWeb Service password 
5.  Check license.txt file for the license key required to activate the plugin.
6.  We have provided the details to obtain the License key in our **readme file**. After downloading or updating the plugin, it is mentioned in point #5 under the installation section in the readme file about how and where to obtain the license key.
We have provided a **license.txt** file in the **root** location of the **plugin folder** which has the license key.  

= Does it create new users in WordPress? =

If a user logs in using their netFORUM Pro credentials and the user does not exist in WordPress, a new WordPress user will be created.

= What should I consider when using this plugin for GDPR compliance? =

The netFORUM Pro Single-Sign On plugin uses a cookie to save a GUID identifier for logged in users and stores the user’s email address from netFORUM as a WordPress user.  This plugin does not store any additional personal information from netFORUM Pro.

= How do I reset user passwords? =

Through this plugin, user passwords will remain in netFORUM Pro and will not be stored in WordPress.  Password changes and resets must be done through netFORUM Pro.

= I am getting a “Client credentials are required” error message. How do I fix this? =

This error commonly occurs when the netFORUM xWeb credentials on the General plugin page are incorrect.  To fix this, review your entered credentials.

= How can I create hyperlinks to netFORUM so that the WordPress user doesn’t have to login again? =

The netFORUM SSO plugin saves the user’s xWeb SSO token in a cookie in WordPress which can then be appended to a netFORUM link.  The token is located in the cookie field `ssotoken`.

We recommend installing a plugin like <a href="https://wordpress.org/plugins/insert-php/" target="_blank">Insert PHP WordPress Plugin</a> to add the below PHP code snippet to your pages or posts. 


`[insert_php]

if(is_user_logged_in()){
  echo 'netForum SSO Token is: ' .$_COOKIE['ssoToken'];
}

[/insert_php]`



= Would this plugin work with netFORUM Enterprise? =

Since each implementation of netFORUM Enterprise is different, the SSO plugin must be customized for each Enterprise implementation.   We offer the netFORUM Enterprise plugin as a paid commercial plugin.  For more information, please [contact us](https://www.fusionspan.com/contact-us/).

== Screenshots ==

1. Enter your netFORUM xWeb URL and credentials in the Plugin Settings.

2. The cache holds previous requests to netFORUM and their results.


== Changelog ==

= 0.5-dev =
* Fixes some bugs.

= 0.4-dev =
* Rewrite complete package.

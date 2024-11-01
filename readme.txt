=== WP Facebook Public Page RSS ===
Contributors: areimann, sideways8
Donate link: http://sideways8.com/plugins/
Tags: facebook, facebook page feed, facebook shortcode, facebook sidebar, facebook widget, facebook rss, rss
Requires at least: 3.4
Tested up to: 3.5.2
Stable 1.0.3
Stable tag: trunk
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A widget and a shortcode to show a public Facebook page feed in a sidebar widget or within a page.

== Description ==

This lets you pull in a Facebook feed into your sidebar (and limit how many words displayed), or with a shortcode into a page or post, and also integrates with a couple of Lightbox plugins.

== Installation ==

1. Find it in the repository
2. Click install and activate it
3. Add a shortcode to a page or create the widget


== Frequently Asked Questions ==

= How do I get my Facebook page's ID number? =

Method 1: To get the Facebook page ID for Sideways8 (https://www.facebook.com/sideways8), you would go to https://graph.facebook.com/sideways8 and the ID is the first thing that shows up.

Method 2: Go to your page, for example: http://facebook.com/sideways8, hit the Edit button.  Then look at the URL in your browser, you should see something like https://www.facebook.com/pages/edit/?id=239209872277&sk=permissions, get the string of numbers after id=, in this case 239209872277.

= Which Lightbox plugins does this integrate with? =

This integrates with any Lightbox that only requires rel="lightbox" added to the image tag.  I have tested this using two plugins, "WP Lightbox 2" and "WP jQuery Lightbox".

= Why don't some images get added to the lightbox? =

Facebook images have really crazy URL's to images like &lt;img src="https://fbexternal-a.akamaihd.net/safe_image.php?d=X111DFqNd3_ETWx-&w=130&h=130&url=http%3A%2F%2Fi3.ytimg.com%2Fvi%2FR0dmLeLCIqc%2Fmaxresdefault.jpg%3Ffeaturen.jpg"&gt; which I'm not wanting to figure out how to get the real image URL. I could figure that out, but it's not worth it to me. 


== Shortcodes ==

= Full shortcode with defaults =

[wp_facebook_public_post_rss facebook_id="239209872277" facebook_number="3" avatar="small" like="true" timestamp="true"]

= Minimum Required Shortcode =

[wp_facebook_public_post_rss facebook_id="239209872277"]

= Number of Facebook Posts =

[wp_facebook_public_post_rss facebook_id="239209872277" facebook_number="3"]

= Avatar options =

[wp_facebook_public_post_rss facebook_id="239209872277" avatar="small,normal,large,none"] (select one, small, normal, large or none)

= Show Like Box =

[wp_facebook_public_post_rss facebook_id="239209872277" like="true,false"] (select one, true or false)

= Show the date =

[wp_facebook_public_post_rss facebook_id="239209872277" timestamp="true,false"] (select one, true or false)


== Screenshots ==

1. Admin Widget showing two feeds
2. Front end widget results
3. Admin Shortcode showing two feed
4. Front end shortcode results
5. Front end showing shortcode results with lightbox integration

== Changelog ==

= 1.0.2 =
* Adds remove all photos option from feed 
* Fixes 'undefined varable' errors in widget

= 1.0.1 =
* Using transients to cache the feed

= 1.0.0 =
* Initial release

== Upgrade Notice ==

=== LH OGP Meta ===
Contributors: shawfactor
Donate link: http://lhero.org/plugins/lh-ogp-meta-tags/
Tags: open graph, ogp, facebook open graph, facebook meta, open graph meta, facebook share, facebook like, facebook
Requires at least: 3.0.
Tested up to: 4.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add OGP and Facebook meta to your website 

== Description ==
This plugin adds accurate Open Graph Meta tags and the facebook specific meta tags to your site. The idea is to keep minimal settings and options as to remain out of your way and in the background while still proving a powerful Open Graph plugin for your WordPress site. 

= Image Handling =
The image specified in settings->LH OGP Meta is the Fallback default image site wide (if a post page or CPT doesn't have an image it will use the fallback). 

The plugin will firstly look for the OGP image specified on the post edit screen image. It will then look for the featured image. If that isn't there either, then it will default to using the image you put into the plugin settings in the admin panel. If THAT isn't there then... well you fail and you won't have an image and we'll put a comment in your source to remind you to add one as Facebook requires one.

= Testing Your Site =
Once you've enabled the plugin head over to Facebook's testing tool and paste in one of your post/page url's or your home page to see what info Facebook is pulling in. This tool is located here: <a href="http://developers.facebook.com/tools/debug">http://developers.facebook.com/tools/debug</a>


Check out [our documentation][docs] for more information. 

All tickets for the project are being tracked on [GitHub][].


[docs]: http://lhero.org/plugins/lh-ogp-meta-tags/
[GitHub]: https://github.com/shawfactor/lh-ogp-meta-tags

Features:

* Select a post image specificly for sharing
* Handles Facebook specific meta like fb:admins and fb:app_id
* maps different users to different article:author properties
* Automatic generation of the description


== Installation ==

1. Upload the entire `lh-ogp-meta-tags` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Optionally navigate to Settings->LH OGP Meta and set the image and optionally, the facebook meta
4. Optionally navigate to the User profile page and add the users facebook url.


== Changelog ==

**1.0 September 08, 2015**  
Initial release.

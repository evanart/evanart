=== WP Live Edit ===
Contributors: olekenneth
Donate link: http://olekenneth.com/
Tags: admin, edit, editor, live, wysiwyg, update, save
Requires at least: 2.6
Tested up to: 3.4.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP Live Edit is Wordpress plugin that enable the user to update the content, live, on the blog.

== Description ==

WP Live Edit is Wordpress plugin that enable the user to update the content, live, on the blog. Don't waste time going back and forth between the admin panel and the site. Just update the content immediately while reading the blog post. The plugin is using WPs strict user control access before enabling this feature, so only the users allow to edit the blog post can do it.

This now works for both posts and pages.

== Installation ==

1. Upload `live-edit`-folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings -> `Live Edit`
4. Set up which field title and content is showed in on the site. Use jQuery selector: (.entry-title, #post-title, etc).

== Frequently Asked Questions ==
= Why does some of my content get duplicated when I use WP Live Edit? =

This i probably because Wordpress insert some other then just the content of the blog. E.g. Like-buttons, rating-widget. If this happens you need to change theme, modify theme or disable just that field.

= How do I find out which field the title or content is? =

You need to inspect the title- or content-field. If you use Firebug, right click on the title and click Inspect with Firebug. Look for ...class="entry-title" or ...id="post-title". If you identify by class use the . (dot) else if you use id use the # (hashtag).

= Does it only work with Twenty Eleven? =

No. But for other themes you need to specify which fields is the title and content. See the question over.

= Why doesn't it save? =

Earlier version had problems with some themes. Update the plugin and try again.

== Screenshots ==

1. WP Live edit in action.
2. Settings panel.

== Changelog ==
= 1.1 =
* Added the ability to edit pages.

= 1.0 =
* Ready to go live! 

= 0.1.1 =
* Fixed problem with some themes where the plugin received wrong id.

= 0.1.0 =
* Added basic functionality.

== Upgrade Notice ==

= 0.0.1 =
* Just install :-)

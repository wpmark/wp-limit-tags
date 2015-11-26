=== Plugin Name ===
Contributors: wpmarkuk, keithdevon
Donate Link: http://markwilkinson.me/saythanks
Tags: tags
Requires at least: 4.2
Tested up to: 4.4
Stable tag: 0.3.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP Limit Tags allows you to set a maximum number of tags for posts.

== Description ==

The plugin provides a settings screen where you can set the maximum number of tags which are allowed to be assigned to posts. It also allows you to choose which post types have restricted tag assignment. It works for all taxonomies that are non-hierarchical.

On the post edit screen, for those posts activated, you will not be allowed to add more than allowed number of tags.

[youtube http://www.youtube.com/watch?v=yWCzGP_l2QE]

== Installation ==

To install the plugin:

1. Upload `wp-limit-tags` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the settings page under Settings > WP Limit Tags

== Frequently Asked Questions ==

None so far!

== Screenshots ==

None so far!

== Changelog ==

= 0.3.2 =
* Prevent users from choosing tags from the tag cloud of most popular tags once the tag limit is reached. Thanks to [ezkay](https://wordpress.org/support/profile/ezkay) for reporting this issue.

= 0.3.1 =
* Make sure it works when tags already present.

= 0.3 =
* Make sure tags get limited as you are typing in the tags input box.

= 0.2 =
* Load JS on the correct admin screen only
* Remove the quick edit from any post type that has limited tags set. This prevents this from being used to add tags this way.

= 0.1 =
* Initial release

== Upgrade Notice ==
Update through the WordPress admin as notified always be sure to make a site backup before upgrading.
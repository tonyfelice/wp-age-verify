=== SEO Age Gate ===
Contributors: tonyfelice
Tags: age, restrict, verify, gate
Requires at least: 3.2
Tested up to: 4.4.1
Stable tag: 0.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple way to ask visitors for their age before viewing your content.

== Description ==

Typical age gate.  This verification/compliance tool is search-engine friendly.  A quesrystring variable is set in the admin console, then appended to all canonicals.  Search engines will follow the canonical version, and once a visitor is identified as a spider, that state is set for the session.

== Installation ==

1. Upload the 'seo-age-gate' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit 'Settings > SEO Age Gate' and adjust your configuration.

== Screenshots ==

1. The settings screen.
2. This is what your visitors see, using the default styling.

== Changelog ==

= 0.0.3 =
* renamed to avoid conflict with fork source

= 0.0.2 =
* Set bypass variable; override rel_canonical(); detect bypass querystring; store in session cookie

= 0.0.1 =
* forked from age-verify by ChaseWiseman

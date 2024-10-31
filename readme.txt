=== Plugin Name ===
Contributors: Darell Sun
Donate link: http://wp-coder.net/donate/
Tags: remote post, sync, publish, xmlrpc, curl, publish, twitter, social
Requires at least: 2.0.2
Tested up to: 3.1.2
Stable tag: 1.1

Post on multipile remote nodes(include your wordpress site, twitter accout) when you publish a new post.

== Description ==

= Post Sync =
The main function of post_sync plugin is to publish post on remote wordpress site, the twitter module will no longer work bacause 
it requires authentication by OAuth, so we have open a twitter services on <a href="http://tunnels.me">tunnels.me</a>, you can download this new
plugin on <a href="http://wordpress.org/extend/plugins/tunnels/">tunnels</a>.
**More info:**

* [post_sync](http://www.wp-coder.net/2011/06/post_sync/)
* Read more great [WordPress tips](http://www.wp-coder.net/blog)

== Installation ==

1. Upload the contents of post_sync.zip to your plugins directory.
2. Activate the plugin
3. There will be a "PostSync" menu on your admin panel, add a new remote site info(include url, username, password) on "add site" submenu, enable this site on "site list" submenu.  
4. That's all. There will be a post on multiple remote website when you publish a new post!

== Frequently Asked Questions ==

= why I can't remote post=

You need to enable the remote publish xmlrpc protocols option on remote website. 


== Screenshots ==

1. The settings page of remote site info
2. Current remote site list you have

== Changelog ==

= 0.1 =
Beta release

=  1.0 =
* Add the Log module on user menu

=  1.1 =
* Add post to twitter module

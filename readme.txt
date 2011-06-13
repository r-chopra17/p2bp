=== P2 ===

A group blog theme for short update messages, inspired by Twitter.

== Description ==

P2 is shorter, better, faster, stronger.
http://p2theme.com/

P2 is a theme for WordPress that transforms a mild-mannered blog into a super-blog with features like inline comments on the homepage, a posting form right on the homepage, inline editing of posts and comments, real-time updates so new posts and comments come in without reloading, and much more.

P2 is available on WordPress.com: http://wordpress.com/signup/?ref=p2
...or you can download it for WordPress.org: http://wordpress.org/extend/themes/p2

You can also check out a demo of the theme in action: http://p2demo.wordpress.com/
If you need P2 support or want to pitch in, drop a line on the forums: http://wordpress.org/tags/p2

== Further reading ==

Matt Mullenweg: How P2 changed Automattic:
http://ma.tt/2009/05/how-p2-changed-automattic/

Official announcement post on WordPress.com:
http://en.blog.wordpress.com/2009/03/11/p2-the-new-prologue/

== Changelog ==

= 1.2.3 =
* Add two new action hooks: p2_post_form and p2_action_links
* Add p2_excerpted_title to provide titles with only whole words
* Run make_clickable later to avoid shortcode conflicts
* Authors widget style fixes
* Allow image upload from front-end regardless of domain and HTTPS setting
* Change include to include_once to avoid conflicts with plugins and clean up require and include calls
* Fix page navigation float clearing and pingback spacing
* Add sticky post styles
* RTL CSS updates
* i18n for user completion JS
* DE translation by Joachim Haydecker
* SK translation by angeloverona
* Updated JA translation from OKAMOTO Wataru

= 1.2.2 - Jan 6 2011 =
* Re-enable is_user_member_of_blog: now works for all cases with back compat
* Add 3.1 support for get_users() with fallback to get_users_of_blog() for back compat
* Hide name mentions taxonomy from Custom Menu form
* Hide screen-reader-text in search form (sidebar and main content)
* Fix empty array warnings
* Misc HTML and CSS validation fixes
* Change discussion author links to use get_comment_author_link
* Add NL translation by Remkus de Vries

= 1.2.1 - Dec 24 2010 =
* Remove is_user_member_of_blog until it's backwards compatible and not dependent on multisite

= 1.2 - Dec 23 2010 =
* Fix inline editing on pages
* Fix comment toggle on pages
* Add title attribute to show @name mention usernames
* Order tags by popularity and display count for tag dropdown; update look to match username dropdown
* Add username suggest autocomplete dropdown based on current users of the site

= 1.1.9 - Nov 30 2010 =
* Remove json.php and use built-in WP JSON functionality
* Add comment paging
* Fix date format bug where an extra 0 was added to the month value
* Fix issues with Custom Menus when adding pages and categories to a menu
* Fix bug where moderated comments did not appear
* Moved changelog.txt to readme.txt
* Misc fixes for 3.1 compatibility
* Portuguese translation update by José Fontainhas (vanillalounge)

1.1.8 - 12 Nov 2010 =
* Show Toggle Comment Threads link on search results view
* Enable auto-parse function for unordered lists in posts and comments
* Fix issue with editing page title from front end
* Remove permalink from pages
* Don't show title for posts in status, quote, or link categories
* Updated Spanish translation by larusalka

= 1.1.7 - 30 Sep 2010 =
* Make quote content links clickable
* Smarter loading for JS libraries
* Update front-end edit form to support editing tags, post title, and quote fields
* Fix display bugs
* Fix issue where posts appear repeatedly on the front page
* Update author name to use display_name instead of nickname on author page
* Uighur translation by Moorshidi (http://microjp.wordpress.com/)
* Chinese translation by joojen (http://code.google.com/p/joojen/)
* Slovenian translation by dz0ny

= 1.1.6 - 30 Aug 2010 =
* Improve textarea auto-resizing in quick post form
* Fix IE8 comment visibility toggle
* Add styles for network signup form
* Fix for IE prompting user when leaving a page after comment
* Fix minor PHP undefined and redeclared errors and deprecated function calls
* Use built-in WordPress comment_form()
* Don't show comments for password-protected posts
* Add automatic feed links support
* Enable custom background
* Replace includes with get_template_part
* Fix missing after_widget in recent comments widget
* Add 404.php template
* Fix post nav for older/newer with correct placement
* Improve search.php message and add inline search form
* Norwegian translation by Peter Holme (http://code.google.com/p/no-wp/)

= 1.1.5 - 8 June 2010 =
* Supported by >= 3.0
* Fixed case where Post Title was used as title for new post
* Fixed media URLs
* Fixed reply link in comment form being appended to wrong post
* Better support for custom header images
* Fixed Edit Page link
* Belorussian translation by  Marcis G. (http://pc.de/)
* Czech translation by Martin Jurica (http://www.jurica.info/)

= 1.1.4 - 8 Jan 2010 =
* Nicolas Friedli French translations
* Two sidebars for custom css
* Updated CSS

= 1.1.3 - 5 Dec 2009 =
* Fix for subdirectory installs and media uploads
* RTL updates
* Template tag updates

= 1.1.2 - 2 Dec 2009 =
* Fix for subdirectory installs (with root directory "homes") and media uploads
* Fix for PHP4 issues with str_ireplace

= 1.1 - 26 Nov 2009 =
* Supported by >=2.9 beta
* @name support
* Proper child theme support
* Completely restructured XHR code
* New files to manage functions, including template-tags.php
* iPhone style support
* Refactored JavaScript
* Speed improvements
* Large modifications to backend JS and theme management
* Options for simple style changes, including a custom header

= 1.0.5 - 12 May 2009 =
* Proper 304 response for ajax updates
* ajax updates for each template page
* Fixed posts may create blank titles still
* added hover affect for postbox
* added postbox to every page
* es translations thanks to Luxiano http://www.luxiano.com.ar

= 1.0.4 - 7 May 2009 =
* Danish translations (props Adamsen)
* Italian translations (props webmaster@giuda.it)
* Update for short tags problem
* Update for navigation issues on single.php
* Fixed keyboard shortcuts menu layout
* Added ability for any title that is not auto-generated, to appear as a large title - this is an option which you can turn on and off
* Made trackbacks only visible from archive pages
* Fix Older/Newer link text
* Move logout link
* Comment permalink fix
* Fixed title count for frontpage showing up on single posts
* Fixed bug if posting to an empty blog

= 1.0.3 - 15 April 2009 =
* Fixed JSON class loading twice
* Japanese Translations (props OKAMOTO Wataru)

= 1.0.2 - 13 April 2009 =
* Lots of bug fixes
* Released to themes directory

= 1.0.1 - 11 Mar 2009 =
* Prevent scheduled posts from appearing
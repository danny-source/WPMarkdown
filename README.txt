=== WP Markdown ===
Contributors: Danny
Tags: markdown, editor, content, formatting, security
Requires at least: 5.2
Tested up to: 6.4
Stable tag: 2.0.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A modern WordPress plugin for handling Markdown processing with support for Markdown Extra features.

== Description ==

WP Markdown is a powerful and lightweight plugin that brings Markdown support to your WordPress site. It processes Markdown content in posts, pages, and comments, making it easy to write and format content using Markdown syntax.

= Features =

* Full Markdown Extra support
* Processes posts, pages, and comments
* Handles footnotes correctly
* Maintains proper HTML structure
* Lightweight and fast
* No external dependencies
* Compatible with WordPress 5.2 and higher
* Secure and well-tested code

= Markdown Extra Features =

* Tables
* Footnotes
* Definition lists
* Fenced code blocks
* Abbreviations
* And more!

= Example =

```markdown
# This is a heading

This is a paragraph with **bold** and *italic* text.

- List item 1
- List item 2

[Link text](https://example.com)

> This is a blockquote

| Table | Header |
|-------|--------|
| Cell  | Cell   |

[^1]: This is a footnote
```

== Installation ==

1. Upload the `wp-markdown` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Start writing your content using Markdown syntax

== Frequently Asked Questions ==

= Do I need to know Markdown to use this plugin? =

Yes, you should be familiar with Markdown syntax to use this plugin effectively. However, Markdown is very easy to learn and there are many resources available online.

= Will this plugin affect my existing content? =

No, the plugin only processes content when it's displayed. Your original content remains unchanged in the database.

= Does this plugin work with other formatting plugins? =

The plugin is designed to work alongside other formatting plugins, but you should test compatibility with your specific setup.

= Is this plugin secure? =

Yes, the plugin uses the well-tested PHP Markdown & Extra library and follows WordPress security best practices. It doesn't collect any user data or make external requests. All processing is done locally on your server.

== Screenshots ==

1. Writing content with Markdown
2. Preview of formatted content
3. Settings page

== Changelog ==

= 2.0.0 =
* Initial release
* Full Markdown Extra support
* WordPress 5.2+ compatibility
* PHP 7.2+ requirement
* Security-focused implementation

== Upgrade Notice ==

= 2.0.0 =
Initial release of WP Markdown with full Markdown Extra support.

== Credits ==

This plugin is based on PHP Markdown & Extra by Michel Fortin.
Original Markdown by John Gruber.

== License ==

This plugin is licensed under the GPL v2 or later.

WP Markdown is based on PHP Markdown & Extra
Copyright (c) 2004-2013 Michel Fortin <http://michelf.ca/>
Based on Markdown
Copyright (c) 2003-2006 John Gruber <http://daringfireball.net/> 
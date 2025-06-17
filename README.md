# WP Markdown

[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/wp-markdown.svg)](https://wordpress.org/plugins/wp-markdown/)
[![WordPress Plugin Downloads](https://img.shields.io/wordpress/plugin/dt/wp-markdown.svg)](https://wordpress.org/plugins/wp-markdown/)
[![WordPress Plugin Rating](https://img.shields.io/wordpress/plugin/r/wp-markdown.svg)](https://wordpress.org/plugins/wp-markdown/)
[![WordPress Plugin Requires](https://img.shields.io/wordpress/plugin/wp-version/wp-markdown.svg)](https://wordpress.org/plugins/wp-markdown/)
[![PHP Version](https://img.shields.io/badge/PHP-7.2+-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v2%2B-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

A modern WordPress plugin for handling Markdown processing with support for Markdown Extra features and Mermaid diagrams.

## Version

**Current Version:** 2.0.7

## Features

* Full Markdown Extra support
* Processes posts, pages, and comments
* Handles footnotes correctly
* Maintains proper HTML structure
* Lightweight and fast
* No external dependencies
* Compatible with WordPress 5.2 and higher
* Secure and well-tested code
* Supports Mermaid diagram blocks (```mermaid ... ```)
* Automatically loads Mermaid.js for diagram rendering
* Security: All HTML tags inside Mermaid blocks are stripped to prevent XSS
* Only allows Mermaid blocks that start with supported diagram types
* Adds a settings page under "Settings > WP Markdown" in the WordPress admin

### Markdown Extra Features

* Tables
* Footnotes
* Definition lists
* Fenced code blocks
* Abbreviations
* And more!

## Example

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

## Requirements

* WordPress 5.2 or higher
* PHP 7.2 or higher

## Installation

1. Upload the `WPMarkdown` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to "Settings > WP Markdown" to view plugin information and usage guide

## Usage

* Write your posts or comments using Markdown syntax. The plugin will automatically convert it to HTML.
* To use Mermaid diagrams, add a code block like this:

    ```mermaid
    graph TD;
        A[Start] --> B[Process];
        B --> C[End];
    ```

* The plugin will render the diagram using Mermaid.js on the frontend.

## Security

* All HTML tags inside Mermaid blocks are stripped for security.
* Only blocks starting with supported Mermaid diagram types are rendered.

## Frequently Asked Questions

### Do I need to know Markdown to use this plugin?

Yes, you should be familiar with Markdown syntax to use this plugin effectively. However, Markdown is very easy to learn and there are many resources available online.

### Will this plugin affect my existing content?

No, the plugin only processes content when it's displayed. Your original content remains unchanged in the database.

### Does this plugin work with other formatting plugins?

The plugin is designed to work alongside other formatting plugins, but you should test compatibility with your specific setup.

### Is this plugin secure?

Yes, the plugin uses the well-tested PHP Markdown & Extra library and follows WordPress security best practices. It doesn't collect any user data or make external requests. All processing is done locally on your server.

## Screenshots

1. Writing content with Markdown
2. Preview of formatted content
3. Settings page

## Changelog

### 2.0.7
* Update: Improved Mermaid block handling and security.
* Update: English comments and documentation.
* Fix: Prevent WordPress smart punctuation from breaking Mermaid syntax.

### 2.0.0 - 2.0.6
* Initial support for Markdown Extra and Mermaid diagrams.
* Added settings page and core information display.

## Upgrade Notice

### 2.0.0
Initial release of WP Markdown with full Markdown Extra support.

## Credits

This plugin is based on PHP Markdown & Extra by Michel Fortin.
Original Markdown by John Gruber.
Mermaid.js by Kite.

## License

This plugin is licensed under the GPL v2 or later.

WP Markdown is based on PHP Markdown & Extra
Copyright (c) 2004-2013 Michel Fortin <http://michelf.ca/>
Based on Markdown
Copyright (c) 2003-2006 John Gruber <http://daringfireball.net/>
Mermaid.js
Copyright (c) 2013-2023 Kite <https://kite.com/>

## Contributing

We welcome contributions! Please see our [Contributing Guidelines](CONTRIBUTING.md) for details.

## Support

For support, please use the [WordPress.org support forums](https://wordpress.org/support/plugin/wp-markdown/).

## Security

If you discover any security related issues, please email [security@da2.35g.tw](mailto:security@da2.35g.tw) instead of using the issue tracker. 
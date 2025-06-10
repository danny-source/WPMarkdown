<?php
/**
 * Plugin Name:       WP Markdown
 * Plugin URI:        https://da2.35g.tw/
 * Description:       A modern WordPress plugin for handling Markdown processing with support for Markdown Extra features.
 * Version:           2.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Danny
 * Author URI:        https://da2.35g.tw/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       wp-markdown
 * Domain Path:       /languages
 *
 * WP Markdown is based on PHP Markdown & Extra
 * Copyright (c) 2004-2013 Michel Fortin <http://michelf.ca/>
 * Based on Markdown
 * Copyright (c) 2003-2006 John Gruber <http://daringfireball.net/>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Main plugin class.
 *
 * A singleton class to ensure the plugin is loaded only once.
 */
final class WPMarkdown_Plugin {

	/**
	 * The single instance of the class.
	 *
	 * @var WPMarkdown_Plugin|null
	 */
	private static ?WPMarkdown_Plugin $instance = null;

	/**
	 * The Markdown parser instance.
	 *
	 * @var \Michelf\MarkdownExtra|null
	 */
	private ?\Michelf\MarkdownExtra $parser = null;

	/**
	 * Main instance.
	 *
	 * Ensures only one instance of the class is loaded.
	 *
	 * @return WPMarkdown_Plugin - The main instance.
	 */
	public static function instance(): WPMarkdown_Plugin {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * Private to prevent direct object creation.
	 */
	private function __construct() {
		$this->define_constants();
		$this->register_hooks();
		$this->init_parser();
	}

	/**
	 * Define plugin constants.
	 */
	private function define_constants(): void {
		define( 'WPMARKDOWN_VERSION', '2.0.0' );
		define( 'WPMARKDOWN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		define( 'WPMARKDOWN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Initialize the Markdown parser.
	 */
	private function init_parser(): void {
		// require_once WPMARKDOWN_PLUGIN_DIR . 'Michelf/Markdown.inc.php';
		require_once WPMARKDOWN_PLUGIN_DIR . 'Michelf/MarkdownExtra.inc.php';
		$this->parser = new \Michelf\MarkdownExtra();
	}

	/**
	 * Register all hooks for the plugin.
	 */
	private function register_hooks(): void {
		// Load text domain for translations
		add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );

		// Post content and excerpts
		add_filter( 'the_content', [ $this, 'render_markdown_content' ], 6 );
		add_filter( 'the_content_rss', [ $this, 'render_markdown_content' ], 6 );
		add_filter( 'get_the_excerpt', [ $this, 'render_markdown_content' ], 6 );
		add_filter( 'get_the_excerpt', 'trim', 7 );
		add_filter( 'the_excerpt', [ $this, 'add_paragraph_tags' ] );
		add_filter( 'the_excerpt_rss', [ $this, 'strip_paragraph_tags' ] );

		// Comments
		add_filter( 'pre_comment_content', [ $this, 'render_markdown_content' ], 6 );
		add_filter( 'get_comment_text', [ $this, 'render_markdown_content' ], 6 );
		add_filter( 'get_comment_excerpt', [ $this, 'render_markdown_content' ], 6 );
		add_filter( 'get_comment_excerpt', [ $this, 'strip_paragraph_tags' ], 7 );

		// Remove WordPress auto-paragraphs
		remove_filter( 'the_content', 'wpautop' );
		remove_filter( 'the_content_rss', 'wpautop' );
		remove_filter( 'the_excerpt', 'wpautop' );
		remove_filter( 'comment_text', 'wpautop', 30 );
		remove_filter( 'comment_text', 'make_clickable' );
	}

	/**
	 * Load plugin textdomain.
	 */
	public function load_textdomain(): void {
		load_plugin_textdomain(
			'wp-markdown',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);
	}

	/**
	 * Process content with Markdown parser.
	 *
	 * @param string $content The original content.
	 * @return string The processed content.
	 */
	public function render_markdown_content( string $content ): string {
		if ( ! $this->parser ) {
			return $content;
		}

		// Set footnote ID prefix based on context
		if ( is_single() || is_page() || is_feed() ) {
			$this->parser->fn_id_prefix = '';
		} else {
			$this->parser->fn_id_prefix = get_the_ID() . '.';
		}

		return $this->parser->transform( $content );
	}

	/**
	 * Add paragraph tags to content if needed.
	 *
	 * @param string $text The content to process.
	 * @return string The processed content.
	 */
	public function add_paragraph_tags( string $text ): string {
		if ( ! preg_match( '{^$|^<(p|ul|ol|dl|pre|blockquote)>}i', $text ) ) {
			$text = '<p>' . $text . '</p>';
			$text = preg_replace( '{\n{2,}}', "</p>\n\n<p>", $text );
		}
		return $text;
	}

	/**
	 * Strip paragraph tags from content.
	 *
	 * @param string $text The content to process.
	 * @return string The processed content.
	 */
	public function strip_paragraph_tags( string $text ): string {
		return preg_replace( '{</?p>}i', '', $text );
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'wp-markdown' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'wp-markdown' ), '1.0.0' );
	}
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @return WPMarkdown_Plugin The instance of the plugin.
 */
function wp_markdown_plugin_run(): WPMarkdown_Plugin {
	return WPMarkdown_Plugin::instance();
}

// Let's get this party started!
wp_markdown_plugin_run(); 
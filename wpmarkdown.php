<?php
/**
 * Plugin Name:       WP Markdown
 * Plugin URI:        https://da2.35g.tw/
 * Description:       A modern WordPress plugin for handling Markdown processing with support for Markdown Extra features.
 * Version:           2.0.7
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

// 版本資訊
define( 'WPMARKDOWN_VERSION', '2.0.7' );
define( 'WPMARKDOWN_MERMAID_VERSION', '10.6.1' );

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

		// Add Mermaid.js script
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_mermaid_script' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_mermaid_script' ] );

		// 添加設定頁面
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );

		// 在外掛列表加入 Settings 連結
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), [ $this, 'add_settings_link' ] );

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
		remove_filter('the_content', 'wptexturize');//文章標題不再自動美化標點，保留原始內容。
		remove_filter('the_title', 'wptexturize');//文章標題不再自動美化標點，保留原始內容。
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
	 * Enqueue Mermaid.js script
	 */
	public function enqueue_mermaid_script(): void {
		wp_enqueue_script(
			'mermaid',
			'https://cdn.jsdelivr.net/npm/mermaid@' . WPMARKDOWN_MERMAID_VERSION . '/dist/mermaid.min.js',
			[],
			WPMARKDOWN_MERMAID_VERSION,
			true
		);

		// Initialize Mermaid
		wp_add_inline_script(
			'mermaid',
			'mermaid.initialize({ startOnLoad: true });'
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

		// 處理 Mermaid 代碼塊
		$content = preg_replace_callback(
			'/```mermaid\n(.*?)\n```/s',
			function( $matches ) {
				$mermaid_code = trim( $matches[1] );
				// Restore <br> to line breaks to prevent WordPress from breaking Mermaid syntax
				$mermaid_code = preg_replace('/<br\s*\/?>/i', "\n", $mermaid_code);
				// Restore en dash (–) to -- and em dash (—) to --- in case of smart punctuation conversion
				// $mermaid_code = str_replace('–', '--', $mermaid_code);
				// $mermaid_code = str_replace('—', '---', $mermaid_code);
				// Security: Remove all HTML tags to prevent XSS
				$mermaid_code = strip_tags($mermaid_code);
				// Only allow blocks that start with supported Mermaid syntax
				if (!preg_match('/^(graph|sequenceDiagram|flowchart|classDiagram|stateDiagram|erDiagram|journey|gantt)/', trim($mermaid_code))) {
					return '';
				}
				// Output the processed code inside the mermaid block
				return '<div class="mermaid">' . $mermaid_code . '</div>';
			},
			$content
		);
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
	 * 添加設定頁面到 WordPress 後台選單
	 */
	public function add_admin_menu(): void {
		add_options_page(
			'WP Markdown Settings', // 頁面標題
			'WP Markdown',          // 選單標題
			'manage_options',       // 權限
			'wp-markdown',          // 選單 slug
			[ $this, 'render_settings_page' ] // 回調函數
		);
	}

	/**
	 * 註冊設定
	 */
	public function register_settings(): void {
		register_setting( 'wp_markdown_settings', 'wp_markdown_settings' );
	}

	/**
	 * 渲染設定頁面
	 */
	public function render_settings_page(): void {
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<div class="card">
				<h2>Core Components Information</h2>
				<table class="form-table">
					<tr>
						<th scope="row">Author</th>
						<td>
							<p>Danny</p>
							<p>Website: <a href="https://da2.35g.tw" target="_blank">https://da2.35g.tw</a></p>
						</td>
					</tr>
					<tr>
						<th scope="row">Plugin Version</th>
						<td><?php echo esc_html( WPMARKDOWN_VERSION ); ?></td>
					</tr>
					<tr>
						<th scope="row">Markdown Extra</th>
						<td>
							<p>Based on PHP Markdown & Extra</p>
							<p>Copyright (c) 2004-2013 Michel Fortin <a href="http://michelf.ca/" target="_blank">http://michelf.ca/</a></p>
							<p>Based on Markdown</p>
							<p>Copyright (c) 2003-2006 John Gruber <a href="http://daringfireball.net/" target="_blank">http://daringfireball.net/</a></p>
						</td>
					</tr>
					<tr>
						<th scope="row">Mermaid.js</th>
						<td>
							<p>Version: <?php echo esc_html( WPMARKDOWN_MERMAID_VERSION ); ?></p>
							<p>Used for rendering Mermaid diagrams</p>
							<p>Source: <a href="https://mermaid.js.org/" target="_blank">https://mermaid.js.org/</a></p>
						</td>
					</tr>
				</table>
			</div>

			<div class="card">
				<h2>Usage Guide</h2>
				<p>Write your content using Markdown syntax in posts or pages, and the plugin will automatically convert it to HTML.</p>
				<p>Mermaid diagram syntax is supported. Usage example:</p>
				<pre><code>```mermaid
graph TD;
    A[Start] --> B[Process];
    B --> C[End];
```</code></pre>
			</div>
		</div>
		<?php
	}

	/**
	 * 在外掛列表加入 Settings 連結
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=wp-markdown">Settings</a>';
		array_unshift( $links, $settings_link );
		return $links;
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
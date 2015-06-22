<?php
/**
 * Cherry Blog Layouts template loader
 *
 * @package   Cherry_Blog_Layouts
 * @author    Cherry Team
 * @license   GPL-2.0+
 * @link      http://www.cherryframework.com/
 * @copyright 2015 Cherry Team
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'Cherry_Blog_Template_Loader' ) ) {

	class Cherry_Blog_Template_Loader {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Temporary storage for main WP_Query during processing custom page template
		 *
		 * @since 1.0.0
		 * @var   boolean
		 */
		public static $temp_query = false;

		/**
		 * Store  custom templates in this property
		 *
		 * @since 1.0.0
		 * @var   array
		 */
		public $templates = array();

		function __construct() {

			// Add a filter to the page attributes metabox to inject our template into the page template cache.
			add_filter( 'page_attributes_dropdown_pages_args', array( $this, 'register_templates' ) );

			// Add a filter to the save post in order to inject out template into the page cache.
			add_filter( 'wp_insert_post_data', array( $this, 'register_templates' ) );

			// Rewrite default blog templates
			add_filter( 'template_include', array( $this, 'view_template' ), 1 );

			// Add your templates to this array.
			$this->templates = array(
				'layout-grid'     => __( 'Blog Grid Layout', 'cherry-blog' ),
				'layout-masonry'  => __( 'Blog Masonry Layout', 'cherry-blog' ),
				'layout-timeline' => __( 'Blog Layout Layout', 'cherry-blog' ),
			);

			// Adding support for theme templates to be merged and shown in dropdown.
			$templates = wp_get_theme()->get_page_templates();
			$templates = array_merge( $templates, $this->templates );

		}

		/**
		 * Load custom templates by name/slug
		 *
		 * Searching priority:
		 * yourtheme/blog-layouts/$name-$slug.php
		 * yourtheme/blog-layouts/$name.php
		 * plugin/templates/$name-$slug.php
		 *
		 * @since 1.0.0
		 *
		 * @param string $slug template slug
		 * @param string $name template unique name
		 */
		public static function get_template( $slug, $name = null ) {

			$template = false;

			if ( $name ) {
				$template = locate_template( array( "{$slug}-{$name}.php" ) );
			}

			if ( ! $template && $name && file_exists( CHERRY_BLOG_DIR . "templates/{$slug}-{$name}.php" ) ) {
				$template = CHERRY_BLOG_DIR . "templates/{$slug}-{$name}.php";
			}

			if ( ! $template ) {
				$template = locate_template( array( "{$slug}.php" ) );
			}

			if ( ! $template && file_exists( CHERRY_BLOG_DIR . "templates/{$slug}.php" ) ) {
				$template = CHERRY_BLOG_DIR . "templates/{$slug}.php";
			}

			$template = apply_filters( 'cherry_blog_get_template', $template, $slug, $name );

			if ( ! $template ) {
				return false;
			}

			return $template;

		}

		/**
		 * Load custom tmpl file
		 *
		 * Searching priority:
		 * yourtheme/blog-layouts/$name-$slug.tmpl
		 * plugin/templates/$name-$slug.tmpl
		 * yourtheme/blog-layouts/$name.tmpl
		 * yourtheme/content/$name-$slug.tmpl
		 * framework/content/$name-$slug.tmpl
		 *
		 * @since 1.0.0
		 *
		 * @param string $slug template slug
		 * @param string $name template unique name
		 */
		public static function get_tmpl( $slug, $name = null ) {

			$template     = false;
			$tmpl_content = '';

			ob_start();

			$child_dir = get_stylesheet_directory();

			if ( file_exists( $child_dir . "/blog-layouts/tmpl/{$slug}-{$name}.tmpl" ) ) {
				$template = $child_dir . "/blog-layouts/tmpl/{$slug}-{$name}.tmpl";
			}

			if ( ! $template && $name && file_exists( CHERRY_BLOG_DIR . "templates/tmpl/{$slug}-{$name}.tmpl" ) ) {
				$template = CHERRY_BLOG_DIR . "templates/tmpl/{$slug}-{$name}.tmpl";
			}

			if ( ! $template && file_exists( $child_dir . "/blog-layouts/tmpl/{$slug}.tmpl" ) ) {
				$template = $child_dir . "/blog-layouts/tmpl/{$slug}.tmpl";
			}

			if ( ! $template && file_exists( CHERRY_BLOG_DIR . "templates/tmpl/{$slug}.tmpl" ) ) {
				$template = CHERRY_BLOG_DIR . "templates/tmpl/{$slug}.tmpl";
			}

			if ( ! $template && $name && file_exists( $child_dir . "/content/{$name}.tmpl" ) ) {
				$template = $child_dir . "/content/{$name}.tmpl";
			}

			if ( ! $template && $name && file_exists( get_template_directory() . "/content/{$name}.tmpl" ) ) {
				$template = get_template_directory() . "/content/{$name}.tmpl";
			}

			if ( $template ) {
				include $template;
				$tmpl_content = ob_get_clean();
			}

			if ( function_exists( 'cherry_do_content' ) ) {
				$output = preg_replace_callback( "/%%.+?%%/", 'cherry_do_content', $tmpl_content );
			}

			echo $output;

		}

		/**
		 * Adds our template to the pages cache in order to trick WordPress
		 * into thinking the template file exists where it doens't really exist.
		 *
		 * @since  1.0.0
		 *
		 * @param  array $atts The attributes for the page attributes dropdown.
		 * @return array $atts The attributes for the page attributes dropdown.
		 */
		public function register_templates( $atts ) {

			// Create the key used for the themes cache.
			$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

			// Retrieve the cache list. If it doesn't exist, or it's empty prepare an array.
			$templates = wp_cache_get( $cache_key, 'themes' );

			if ( empty( $templates ) ) {
				$templates = array();
			}

			// Since we've updated the cache, we need to delete the old cache.
			wp_cache_delete( $cache_key , 'themes');

			// Now add our template to the list of templates by merging our templates
			// with the existing templates array from the cache.
			$templates = array_merge( $templates, $this->templates );

			// Add the modified cache to allow WordPress to pick it up for listing available templates.
			wp_cache_add( $cache_key, $templates, 'themes', 1800 );

			return $atts;
		}

		/**
		 * Include specific layout template file
		 *
		 * @since  1.0.0
		 * @param  string $template default template name
		 */
		function view_template( $template ) {

			// Check, if is posts listing page
			$blog_template = $this->get_blog_template();
			if ( false !== $blog_template ) {
				return $blog_template;
			}

			// check if is custom page template
			$page_template = $this->get_page_template();
			if ( false !== $page_template ) {
				return $page_template;
			}

			return $template;

		}

		/**
		 * Check if we need rewrite template for the blog and get it
		 *
		 * @since  1.0.0
		 *
		 * @return bool|string  boolean false if no need to rewrite or template path
		 */
		public function get_blog_template() {

			$is_enabled = Cherry_Blog_Layouts::get_option( 'blog-layout-enabled', 'false' );

			if ( 'true' !== $is_enabled ) {
				return false;
			}

			$conditionals = Cherry_Blog_Layouts::conditionals();

			$allowed_pages = Cherry_Blog_Layouts::get_option( 'blog-layout-pages', $conditionals );

			$rewrite_template = false;

			foreach ( $conditionals as $conditional ) {
				if ( in_array( $conditional, $allowed_pages ) && call_user_func( $conditional ) ) {
					$rewrite_template = true;
					Cherry_Blog_Layouts::$is_custom_layout = true;
					break;
				}
			}

			$layout_type = Cherry_Blog_Layouts::get_option( 'blog-layout-type', 'grid' );

			if ( true === $rewrite_template ) {
				return $this->get_template( 'layout', $layout_type );
			}

			return false;
		}

		/**
		 * Check if we need rewrite template for the page and get it
		 *
		 * @since  1.0.0
		 *
		 * @return bool|string  boolean false if no need to rewrite or template path
		 */
		public function get_page_template() {

			global $post;

			if ( ! is_page( $post ) ) {
				return false;
			}

			$page_template_meta = get_post_meta( $post->ID, '_wp_page_template', true );

			if ( ! isset( $this->templates[ $page_template_meta ] ) ) {
				return false;
			}

			$template_pieces = explode( '-', $page_template_meta );

			if ( is_array( $template_pieces ) && 2 == count( $template_pieces ) ) {
				Cherry_Blog_Layouts::$is_custom_page = true;
				return $this->get_template( $template_pieces[0], $template_pieces[1] );
			}

			return false;
		}

		/**
		 * Prepare WP query for page templates
		 *
		 * @since 1.0.0
		 */
		public static function prepare_query() {

			global $wp_query;

			self::$temp_query = $wp_query;

			$paged = get_query_var( 'paged' );

			if ( ! $paged ) {
				$paged = 1;
			}

			$args = array(
				'pagename' => false,
				'name'     => false,
				'paged'    => $paged,
				'posts_per_page' => Cherry_Blog_Layouts::get_option( 'blog-layout-post-per-page', 9 )
			);

			$wp_query = new WP_Query( $args );

		}

		/**
		 * Restore main WP query after template processing
		 *
		 * @since 1.0.0
		 */
		public static function restore_query() {

			if ( false === self::$temp_query ) {
				return;
			}

			global $wp_query;
			$wp_query = self::$temp_query;
			wp_reset_postdata();
			wp_reset_query();

		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance )
				self::$instance = new self;

			return self::$instance;

		}

	}

	Cherry_Blog_Template_Loader::get_instance();

}
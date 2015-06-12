<?php
/**
 * Cherry Blog Layouts additional template hooks and service function
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

if ( ! class_exists( 'Cherry_Blog_Layouts_Tools' ) ) {

	class Cherry_Blog_Layouts_Tools {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		function __construct() {
			add_filter( 'cherry_blog_layout_wrapper_class', array( $this, 'wrapper_classes' ), 10, 2 );
			add_filter( 'cherry_get_page_layout', array( $this, 'rewrite_layout' ) );
			add_filter( 'cherry_get_page_grid_type', array( $this, 'rewrite_grid_type' ) );
		}

		/**
		 * Get item CSS class
		 *
		 * @since  1.0.0
		 * @param  array|string $classes passed user classes
		 */
		public static function item_class( $classes = array() ) {

			if ( ! is_array( $classes ) ) {
				$classes = array( $classes );
			}

			$columns = Cherry_Blog_Layouts::get_option( 'blog-layout-columns', 3 );
			$columns = absint( $columns );

			if ( 0 === $columns ) {
				$columns = 1;
			}

			$md_class = floor( 12 / $columns );
			$xs_class = floor( $md_class / 2 );

			$classes[] = 'col-md-' . $md_class;
			$classes[] = 'col-sm-' . $md_class;
			$classes[] = 'col-xs-' . $xs_class;

			$class = implode( ' ', array_filter( $classes ) );

			return $class;

		}

		/**
		 * Get additional wrapper classes
		 *
		 * @since 1.0.0
		 *
		 * @param string $class default CSS class
		 * @param string $type  layout type
		 */
		public function wrapper_classes( $class, $type ) {

			if ( 'timeline' !== $type ) {
				$columns  = Cherry_Blog_Layouts::get_option( 'blog-layout-columns', 3 );
				$class   .= ' columns-' . $columns;
			}

			return $class;
		}

		/**
		 * Rewrite page layout for pages with custom blog
		 *
		 * @since  1.0.0
		 *
		 * @param  string $layout default layout
		 * @return string         rewritten layout (if needed)
		 */
		public function rewrite_layout( $layout ) {

			if ( ! Cherry_Blog_Layouts::$is_custom_layout ) {
				return $layout;
			}

			$custom_layout = Cherry_Blog_Layouts::get_option( 'blog-layout-sidebar-position', 'inherit' );

			if ( ! $custom_layout || 'inherit' == $custom_layout ) {
				return $layout;
			}

			return $custom_layout;

		}

		/**
		 * Rewrite page grid type for custom blog pages
		 *
		 * @since  1.0.0
		 *
		 * @param  array  $grid_type  default page grid type
		 * @return array              rewritten grid type
		 */
		public function rewrite_grid_type( $grid_type ) {

			if ( ! Cherry_Blog_Layouts::$is_custom_layout ) {
				return $grid_type;
			}

			$content_grid = Cherry_Blog_Layouts::get_option( 'blog-layout-grid-type', 'inherit' );

			if ( ! $content_grid || 'inherit' == $content_grid ) {
				return $grid_type;
			}

			if ( ! is_array( $grid_type ) ) {
				return array( 'content' => $content_grid );
			}

			$grid_type['content'] = $content_grid;

			return $grid_type;

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

	Cherry_Blog_Layouts_Tools::get_instance();

}
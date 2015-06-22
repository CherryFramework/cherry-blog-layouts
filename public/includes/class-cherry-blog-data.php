<?php
/**
 * Cherry Blog Layouts data
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

if ( ! class_exists( 'Cherry_Blog_Layouts_Data' ) ) {

	class Cherry_Blog_Layouts_Data {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		function __construct() {

		}

		public static function filter_render( $custom_class = '' ) {
			$html = '';

			$taxonomy_type = Cherry_Blog_Layouts::get_option( 'blog-layout-filter', 'categories' );
			$layout_type = Cherry_Blog_Layouts::get_option( 'blog-layout-type', 'masonry' );

			switch ( $layout_type ) {
				case 'grid':
					$filter_class = 'grid-layout-filter';
					break;
				case 'masonry':
					$filter_class = 'masonry-layout-filter';
					break;
				case 'timeline':
					$filter_class = 'timeline-layout-filter';
					break;
			}
			switch ( $taxonomy_type ) {
				case 'none':
					$terms = null;
					break;
				case 'categories':
					$terms = get_categories();
					break;
				case 'tags':
					$terms = get_tags();
					break;
			}
			if( isset( $terms ) ){
				$html .= '<ul class="taxonomy-filter '. $filter_class . ' ' . $custom_class . '">';
					if( get_option( 'show_on_front' ) == 'page' ){
						$all_terms = get_permalink( get_option('page_for_posts') );
					}else{
						$all_terms =get_bloginfo('url', 'display');
					}
					$html .= '<li><a href="' . $all_terms . '/">' . apply_filters( 'cherry_blog_layout_all_terms_text', __('All', 'cherry-blog') ) .'</a></li>';
						foreach( $terms as $term ){
							switch ( $taxonomy_type ) {
								case 'categories':
									$term_permalink = get_category_link( $term->term_taxonomy_id );
									break;
								case 'tags':
									$term_permalink = get_tag_link( $term->term_taxonomy_id );
									break;
							}
							$html .= '<li><a href="' . $term_permalink . '">' . $term->name . '</a></li>';
						}
				$html .= '</ul>';
			}

			return $html;
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

	Cherry_Blog_Layouts_Data::get_instance();

}
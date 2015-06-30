<?php
/**
 * Cherry Blog Shortcode.
 *
 * @package   Cherry_Blog_Layouts
 * @author    Cherry Team
 * @license   GPL-2.0+
 * @link      http://www.cherryframework.com/
 * @copyright 2014 Cherry Team
 */

/**
 * Class for Blog shortcode.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Cherry_Blog_Layout_Shortcode' ) ) {

	class Cherry_Blog_Layout_Shortcode {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Shortcode name.
		 *
		 * @since 1.0.0
		 * @var   string
		 */
		public $name = 'blog';

		function __construct() {

			// Register shortcode on 'init'.
			add_action( 'init', array( $this, 'register_shortcode' ) );

			// Add shortcode to editor
			add_filter( 'cherry_shortcodes/data/shortcodes', array( $this, 'add_to_editor' ) );

		}

		/**
		 * Registers the [$this->name] shortcode.
		 *
		 * @since 1.0.0
		 */
		public function register_shortcode() {

			/**
			 * Filters a shortcode name.
			 *
			 * @since 1.0.0
			 * @param string $this->name Shortcode name.
			 */
			$tag = apply_filters( $this->name . '_shortcode_name', $this->name );

			add_shortcode( $tag, array( $this, 'do_shortcode' ) );
		}

		/**
		 * Add blog layout shortcode to Cherry Shortcodes editor
		 *
		 * @since  1.0.0
		 * @param  array  $shortcodes  already added shortcodes
		 * @return array
		 */
		public function add_to_editor( $shortcodes ) {

			$terms = get_terms( 'group' );

			$terms_list = array();
			if ( ! is_wp_error( $terms ) ) {
				$terms_list = wp_list_pluck( $terms, 'name', 'slug' );
			}

			$sizes_list = array();
			if ( class_exists( 'Cherry_Shortcodes_Tools' ) && method_exists( 'Cherry_Shortcodes_Tools', 'image_sizes' ) ) {
				$sizes_list = Cherry_Shortcodes_Tools::image_sizes();
			}

			$shortcodes[ $this->name ] = array(
				'name'  => __( 'Blog Layout', 'cherry-blog-layouts' ), // Shortcode name.
				'desc'  => __( 'Cherry blog layout shortcode', 'cherry-blog-layouts' ),
				'type'  => 'single', // Can be 'wrap' or 'single'. Example: [b]this is wrapped[/b], [this_is_single]
				'group' => 'content', // Can be 'content', 'box', 'media' or 'other'. Groups can be mixed
				'atts'  => array( // List of shortcode params (attributes).
					'limit' => array(
						'type'    => 'slider',
						'min'     => -1,
						'max'     => 100,
						'step'    => 1,
						'default' => 3,
						'name'    => __( 'Limit', 'cherry-blog-layouts' ),
						'desc'    => __( 'Maximum number of posts.', 'cherry-blog-layouts' )
					),
					'order' => array(
						'type' => 'select',
						'values' => array(
							'desc' => __( 'Descending', 'cherry-blog-layouts' ),
							'asc'  => __( 'Ascending', 'cherry-blog-layout' )
						),
						'default' => 'DESC',
						'name' => __( 'Order', 'cherry-blog-layouts' ),
						'desc' => __( 'Posts order', 'cherry-blog-layouts' )
					),
					'orderby' => array(
						'type' => 'select',
						'values' => array(
							'none'          => __( 'None', 'cherry-blog-layouts' ),
							'id'            => __( 'Post ID', 'cherry-blog-layouts' ),
							'author'        => __( 'Post author', 'cherry-blog-layouts' ),
							'title'         => __( 'Post title', 'cherry-blog-layouts' ),
							'name'          => __( 'Post slug', 'cherry-blog-layouts' ),
							'date'          => __( 'Date', 'cherry-blog-layouts' ),
							'modified'      => __( 'Last modified date', 'cherry-blog-layouts' ),
							'rand'          => __( 'Random', 'cherry-blog-layouts' ),
							'comment_count' => __( 'Comments number', 'cherry-blog-layouts' ),
						),
						'default' => 'date',
						'name'    => __( 'Order by', 'cherry-blog-layouts' ),
						'desc'    => __( 'Order posts by', 'cherry-blog-layouts' )
					),
					'category' => array(
						'type'     => 'select',
						'multiple' => true,
						'values'   => $terms_list,
						'default'  => '',
						'name'     => __( 'Category', 'cherry-blog-layouts' ),
						'desc'     => __( 'Select categories to show posts from', 'cherry-blog-layouts' ),
					),
					'paged' => array(
						'type'    => 'bool',
						'default' => 'no',
						'name'    => __( 'Show pager', 'cherry-blog-layouts' ),
						'desc'    => __( 'Show paged navigation or not', 'cherry-blog-layouts' ),
					),
					'layout' => array(
						'type' => 'select',
						'values' => array(
							'grid'     => __( 'Grid', 'cherry-blog-layouts' ),
							'masonry'  => __( 'Masonry', 'cherry-blog-layouts' ),
							'timeline' => __( 'Timeline', 'cherry-blog-layouts' )
						),
						'default' => 'date',
						'name'    => __( 'Layout', 'cherry-blog-layouts' ),
						'desc'    => __( 'Select output layout format', 'cherry-blog-layouts' )
					),
					'class'   => array(
						'default' => '',
						'name'    => __( 'Class', 'cherry-blog-layouts' ),
						'desc'    => __( 'Extra CSS class', 'cherry-blog-layouts' )
					),
				),
				'icon'     => 'th', // Custom icon (font-awesome).
				'function' => array( $this, 'do_shortcode' ) // Name of shortcode function.
			);

			return $shortcodes;

		}

		/**
		 * Callback function for blog shortcode
		 *
		 * @since  1.0.0
		 * @param  array  $atts    shortcode attributes array
		 * @param  string $content shortcode inner content
		 * @return string
		 */
		public function do_shortcode( $atts, $content = null ) {

			// Set up the default arguments.
			$defaults = array(
				'limit'    => 3,
				'orderby'  => 'date',
				'order'    => 'DESC',
				'category' => '',
				'paged'    => 0,
				'layout'   => 'grid',
				'class'    => '',
			);

			/**
			 * Parse the arguments.
			 *
			 * @link http://codex.wordpress.org/Function_Reference/shortcode_atts
			 */
			$atts = shortcode_atts( $defaults, $atts, $this->name );

			$query_args = array();

			$query_args['posts_per_page']   = $atts['limit'];
			$query_args['orderby']          = $atts['orderby'];
			$query_args['order']            = $atts['order'];
			$query_args['suppress_filters'] = false;

			if ( ! empty( $args['category'] ) ) {

				$cat = str_replace( ' ', ',', $args['category'] );
				$cat = explode( ',', $cat );

				if ( is_array( $cat ) ) {
					$query_args['tax_query'] = array(
						array(
							'taxonomy' => 'category',
							'field'    => 'slug',
							'terms'    => $cat
						)
					);
				}
			} else {
				$query_args['tax_query'] = false;
			}

			if ( $atts['paged'] ) {

				if ( get_query_var('paged') ) {
					$query_args['paged'] = get_query_var('paged');
				} elseif ( get_query_var('page') ) {
					$query_args['paged'] = get_query_var('page');
				} else {
					$query_args['paged'] = 1;
				}

			}

			$posts_query = new WP_Query( $query_args );

			if ( ! $posts_query->have_posts() ) {
				return __( 'No posts found', 'cherry-blog-layouts' );
			}

			$allowed_layouts = array( 'grid', 'masonry', 'timeline' );
			$layout = ( in_array( $atts['layout'], $allowed_layouts ) ) ? $atts['layout'] : 'grid';

			ob_start();

			echo Cherry_Blog_Layouts_Data::filter_render();

			if ( 'timeline' == $layout ) {
				echo '<div class="timeline-wpapper">';
				echo '<span class="timeline-line"></span>';
			}

			$post_counter = 0;
			$break_point_date = '';
			$date_format  = Cherry_Blog_Layouts::get_option( 'blog-layout-timeline-breakpoint-date-format', 'l, F j, Y' );
			$marker_date_format  = Cherry_Blog_Layouts::get_option( 'blog-layout-timeline-marker-date-format', 'F j, Y' );
			$breakpoint  = Cherry_Blog_Layouts::get_option( 'blog-layout-timeline-breakpoint', 'timeline' );
			$use_breakpoints  = Cherry_Blog_Layouts::get_option( 'blog-layout-use-timeline-breakpoint', 'true' );
			$marker_date_label = Cherry_Blog_Layouts::get_option( 'blog-layout-show-marker-date', 'false' );

			while ( $posts_query->have_posts() ) {
				$posts_query->the_post();
				$template_file = Cherry_Blog_Template_Loader::get_template( 'layout-' . $layout, 'content' );
				include $template_file;
			}
			$posts = ob_get_clean();

			return sprintf( '<div class="blog-layout %2$s-layout">%1$s</div>', $posts, $layout );

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

	Cherry_Blog_Layout_Shortcode::get_instance();

}
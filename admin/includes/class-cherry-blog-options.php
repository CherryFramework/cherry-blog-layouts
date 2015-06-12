<?php
/**
 * Cherry Blog Layouts options
 *
 * @package   Cherry_Blog_Layouts
 * @author    Cherry Team
 * @license   GPL-2.0+
 * @link      http://www.cherryframework.com/
 * @copyright 2015 Cherry Team
 */

if ( ! class_exists( 'Cherry_Blog_Layout_Options' ) ) {

	class Cherry_Blog_Layout_Options {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		public function __construct() {

			add_filter( 'cherry_defaults_settings', array( $this, 'add_options') );

		}

		/**
		 * Add Blog Layout options
		 *
		 * @since  1.0.0
		 *
		 * @param  array $sections  default sections array
		 * @return array            filtered sections array
		 */
		public function add_options( $sections ) {

			$layout_options = array(
				'blog-layout-enabled' => array(
					'type'			=> 'switcher',
					'title'			=> __( 'Enable custom blog layout', 'cherry-blog' ),
					'label'			=> '',
					'description'	=> '',
					'hint'			=> array(
						'type'    => 'text',
						'content' => __( 'Enable/disable custom blog layout', 'cherry-blog' )
					),
					'value'			=> 'false',
				),
				'blog-layout-type' => array(
					'type'			=> 'select',
					'title'			=> __( 'Custom blog layout type', 'cherry-blog' ),
					'label'			=> '',
					'description'	=> '',
					'hint'			=> array(
						'type'    => 'text',
						'content' => __( 'Select custom blog layout type', 'cherry-blog' )
					),
					'value'			=> 'grid',
					'class'			=> 'width-full',
					'options'		=> array(
						'grid'     => __( 'Grid', 'cherry-blog' ),
						'masonry'  => __( 'Masonry', 'cherry-blog' ),
						'timeline' => __( 'Timeline', 'cherry-blog' )
					)
				),
				'blog-layout-pages' => array(
					'type'			=> 'checkbox',
					'title'			=> __( 'Use on pages', 'cherry-blog' ),
					'label'			=> '',
					'description'	=> '',
					'hint'			=>  array(
						'type'		=> 'text',
						'content'	=> __('Please specify pages that will use custom blog layout.', 'cherry-blog'),
					),
					'class'			=> '',
					'value'			=> array(
						'is_home',
						'is_post_type_archive',
						'is_category',
						'is_tag',
						'is_author',
						'is_date'
					),
					'options'		=> array(
						'is_home'              => __( 'Blog page', 'cherry-blog' ),
						'is_post_type_archive' => __( 'Custom Post type archive', 'cherry-blog' ),
						'is_category'          => __( 'Blog category archive', 'cherry-blog' ),
						'is_tag'               => __( 'Blog tags archive', 'cherry-blog' ),
						'is_author'            => __( 'Author archive', 'cherry-blog' ),
						'is_date'              => __( 'Date archive', 'cherry-blog' )
					)
				),
				'blog-layout-columns' => array(
					'type'			=> 'slider',
					'title'			=> __( 'Columns number', 'cherry' ),
					'description'	=> __( 'Specify custom blog layout columns number', 'cherry' ),
					'max_value'		=> 12,
					'min_value'		=> 1,
					'value'			=> 3,
				),
				'blog-layout-grid-type' => array(
					'type'			=> 'radio',
					'title'			=> __( 'Grid type', 'cherry' ),
					'description'	=> __( 'Select layout pattern for pages with custom blog layout. Wide layout will fit window width. Boxed layout will have fixed width.', 'cherry' ),
					'value'			=> 'inherit',
					'display_input'	=> false,
					'options'		=> array(
						'inherit' => array(
							'label'   => __( 'Inherit', 'cherry' ),
							'img_src' => PARENT_URI . '/lib/admin/assets/images/svg/inherit.svg',
						),
						'wide' => array(
							'label'   => __( 'Wide', 'cherry' ),
							'img_src' => PARENT_URI . '/lib/admin/assets/images/svg/grid-type-fullwidth.svg',
						),
						'boxed' => array(
							'label'   => __( 'Boxed', 'cherry' ),
							'img_src' => PARENT_URI . '/lib/admin/assets/images/svg/grid-type-container.svg',
						),
					),
				),
				'blog-layout-sidebar-position' => array(
					'type'        => 'radio',
					'title'       => __( 'Custom Blog sidebars', 'cherry-blog' ),
					'description' => '',
					'hint'        => array(
						'type'    => 'text',
						'content' => __( 'You can choose if you want to display sidebars and how you want to display them.', 'cherry' ),
					),
					'value'         => 'inherit',
					'display_input' => false,
					'options'       => array(
						'inherit' => array(
							'label'   => __( 'Inherit', 'cherry' ),
							'img_src' => PARENT_URI . '/lib/admin/assets/images/svg/inherit.svg',
						),
						'sidebar-content' => array(
							'label'   => __( 'Left sidebar', 'cherry' ),
							'img_src' => PARENT_URI . '/lib/admin/assets/images/svg/page-layout-left-sidebar.svg',
						),
						'content-sidebar' => array(
							'label'   => __( 'Right sidebar', 'cherry' ),
							'img_src' => PARENT_URI . '/lib/admin/assets/images/svg/page-layout-right-sidebar.svg',
						),
						'sidebar-content-sidebar' => array(
							'label'   => __( 'Left and right sidebar', 'cherry' ),
							'img_src' => PARENT_URI . '/lib/admin/assets/images/svg/page-layout-both-sidebar.svg',
						),
						'sidebar-sidebar-content' => array(
							'label'   => __( 'Two sidebars on the left', 'cherry' ),
							'img_src' => PARENT_URI . '/lib/admin/assets/images/svg/page-layout-sameside-left-sidebar.svg',
						),
						'content-sidebar-sidebar' => array(
							'label'   => __( 'Two sidebars on the right', 'cherry' ),
							'img_src' => PARENT_URI . '/lib/admin/assets/images/svg/page-layout-sameside-right-sidebar.svg',
						),
						'no-sidebar' => array(
							'label'   => __( 'No sidebar', 'cherry' ),
							'img_src' => PARENT_URI . '/lib/admin/assets/images/svg/page-layout-fullwidth.svg',
						)
					)
				)
			);

			$menu_options = apply_filters( 'cherry_blog_layout_options', $layout_options );

			$sections['blog-layout-options-section'] = array(
				'name'			=> __( 'Blog layout', 'cherry-blog' ),
				'icon'			=> 'dashicons dashicons-arrow-right',
				'parent'		=> 'blog-section',
				'priority'		=> 41,
				'options-list'	=> $layout_options
			);

			return $sections;
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

	Cherry_Blog_Layout_Options::get_instance();

}
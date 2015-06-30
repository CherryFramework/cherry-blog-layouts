<?php
/**
 * Timeline Layout type template
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

?>
<div class="<?php echo apply_filters( 'cherry_blog_layout_wrapper_class', 'timeline-layout', 'timeline' ); ?>"<?php echo Cherry_Blog_Layouts_Tools::wrapper_attrs() ?>>
	<?php echo Cherry_Blog_Layouts_Data::filter_render(); ?>
	<div class="timeline-wpapper">
		<span class="timeline-line"></span>
		<?php
			$post_counter = 0;
			$break_point_date = '';
			$date_format  = Cherry_Blog_Layouts::get_option( 'blog-layout-timeline-breakpoint-date-format', 'l, F j, Y' );
			$marker_date_format  = Cherry_Blog_Layouts::get_option( 'blog-layout-timeline-marker-date-format', 'F j, Y' );
			$breakpoint  = Cherry_Blog_Layouts::get_option( 'blog-layout-timeline-breakpoint', 'timeline' );
			$use_breakpoints  = Cherry_Blog_Layouts::get_option( 'blog-layout-use-timeline-breakpoint', 'true' );
			$marker_date_label = Cherry_Blog_Layouts::get_option( 'blog-layout-show-marker-date', 'false' );

			while ( have_posts() ) : the_post();

				$template_file = Cherry_Blog_Template_Loader::get_template( 'layout-timeline', 'content' );
				include $template_file;

			endwhile;

		?>
	<div class="clear"></div>
	<?php the_posts_pagination(); ?>
	</div>
</div>
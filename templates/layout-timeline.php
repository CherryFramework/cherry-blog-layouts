<?php
/**
 * Timeline Layout type template
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}?>
<div class="<?php echo apply_filters( 'cherry_blog_layout_wrapper_class', 'timeline-layout', 'timeline' ); ?>"<?php echo Cherry_Blog_Layouts_Tools::wrapper_attrs() ?>>
	<?php
	$parsed_options = Cherry_Blog_Layouts_Data::get_parsed_options();
	echo Cherry_Blog_Layouts_Data::filter_render( $parsed_options['filter_type'] ); ?>
	<div class="timeline-wpapper">
		<span class="timeline-line"></span>
		<?php

			$post_counter = 0;
			$break_point_date = '';

			while ( have_posts() ) : the_post();
				$template_file = Cherry_Blog_Template_Loader::get_template( 'layout-timeline', 'content' );
				include $template_file;
			endwhile;
		?>
	<div class="clear"></div>
	</div>
</div>
<?php the_posts_pagination(); ?>
<?php
/**
 * Grid Layout type template
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

//Cherry_Blog_Template_Loader::prepare_query();
?>
<div class="<?php echo apply_filters( 'cherry_blog_layout_wrapper_class', 'timeline-layout', 'timeline' ); ?>"<?php echo Cherry_Blog_Layouts_Tools::wrapper_attrs() ?>>
	<?php echo Cherry_Blog_Layouts_Data::filter_render(); ?>
	<div class="timeline-wpapper">
		<span class="timeline-line"></span>
		<?php
			global $wp_query;
			$post_counter = 0;
			$break_point_date = '';
			$date_format  = Cherry_Blog_Layouts::get_option( 'blog-layout-timeline-breakpoint-date-format', 'l, F j, Y' );
			$marker_date_format  = Cherry_Blog_Layouts::get_option( 'blog-layout-timeline-marker-date-format', 'F j, Y' );
			$breakpoint  = Cherry_Blog_Layouts::get_option( 'blog-layout-timeline-breakpoint', 'timeline' );
			$use_breakpoints  = Cherry_Blog_Layouts::get_option( 'blog-layout-use-timeline-breakpoint', 'true' );
			$marker_date_label = Cherry_Blog_Layouts::get_option( 'blog-layout-show-marker-date', 'false' );

			while ( have_posts() ) : the_post();
				$post_id = get_the_ID();
				$format = get_post_format();
				if ( ! $format ) {
					$format = 'standard';
				}
				switch ( $breakpoint ) {
					case 'year':
						$current_date = get_the_date('Y');
						$date_format = 'Y';
						break;
					case 'month':
						$current_date = get_the_date('F Y');
						break;
					case 'day':
						$current_date = get_the_date('d F Y');
						break;
				}

				$current_date_format = get_the_date( $date_format );

				if ( 'true' === $use_breakpoints ) {
					if( !$break_point_date ){
						echo breakpoint_render( $current_date_format );
						?><section class="timeline-group"><?php
						$break_point_date = $current_date;
					} elseif ( strtotime( $break_point_date ) > strtotime( $current_date ) ) {
						?><div class="clear"></div></section><?php
						echo breakpoint_render( $current_date_format );
						?><section class="timeline-group"><?php
						$break_point_date = $current_date;
						$post_counter = 0;
					}
				}

				( $post_counter % 2 == 0 ) ? $even = ' odd' : $even = ' even';

				?> <article class="<?php echo apply_filters( 'cherry_blog_layout_item_class', 'timeline-layout-item', 'masonry' ); echo $even; ?>">
					<div class="inner">
				<?php

				$marker_date = ( 'true' === $marker_date_label ) ? get_the_date( $marker_date_format ) : '' ;

				echo apply_filters( 'cherry_blog_layout_timeline_marker', '<div class="marker"><span>' . $marker_date . '</span></div>', 1 );
				echo apply_filters( 'cherry_blog_layout_timeline_arrow', '<div class="arrow"><span></span></div>' );

				$name = apply_filters( 'cherry_blog_layout_template_name', $format, 'timeline' );
				Cherry_Blog_Template_Loader::get_tmpl( 'layout', $name );
				?> </div></article> <?php

				$post_counter ++;

			endwhile;
			the_posts_pagination();

			function breakpoint_render( $date_format = '' ){
				return '<div class="timeline-breakpiont">' . $date_format . '</div>';
			}
		?>
	<div class="clear"></div>
	</div>
</div>
<?php
//Cherry_Blog_Template_Loader::restore_query();
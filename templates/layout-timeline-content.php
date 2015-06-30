<?php
/**
 * contentTimeline Layout type template
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}
global $post;

$post_id = $post->ID;
$format  = get_post_format( $post_id );

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
<?php
/**
 * Content Grid Layout type template
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

global $post;

$post_id = $post->ID;
$format = get_post_format( $post_id );

if ( ! $format ) {
	$format = 'standard';
}
?><article class="<?php echo Cherry_Blog_Layouts_Tools::item_class( 'grid-layout-item' ); ?>"><?php
	$name = apply_filters( 'cherry_blog_layout_template_name', $format, 'grid' );

	$prefix = ( !empty( $parsed_options['template_type'] ) ) ? '-'.$parsed_options['template_type'] : '';
	Cherry_Blog_Template_Loader::get_tmpl( 'layout-grid'.$prefix, $name );

?></article><?php
$post_counter++;
<?php
/**
 * Content Grid Layout type template.
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

global $post;

$post_id = $post->ID;
$format = get_post_format( $post_id );

if ( ! $format ) {
	$format = 'standard';
}

switch ( $parsed_options['grid_column'] ) {
	case 'grid-2':
		$columns = 2;

		break;
	case 'grid-3':
		$columns = 3;

		break;
	case 'grid-4':
		$columns = 4;

		break;
	case 'grid-6':
		$columns = 6;

		break;
}

if( $columns === $post_counter ) {
	?></div><?php
	$post_counter = 0;
}

if( 0 === $post_counter ) {
	?><div class="row"><?php
}

$post_counter++;
?><article class="<?php echo Cherry_Blog_Layouts_Tools::item_class( 'grid-layout-item', $parsed_options['grid_column'] ); ?>"><?php
	$name = apply_filters( 'cherry_blog_layout_template_name', $format, 'grid' );

	$prefix = ( ! empty( $parsed_options['template_type'] ) ) ? '-' . $parsed_options['template_type'] : '';

	Cherry_Blog_Template_Loader::get_tmpl( 'layout-grid' . $prefix, $name );
?></article>

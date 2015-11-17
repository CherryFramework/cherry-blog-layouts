<?php
/**
 * Masonry layout type content template.
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
$format  = get_post_format( $post_id );
if ( ! $format ) {
	$format = 'standard';
}
?>
<article class="<?php echo apply_filters( 'cherry_blog_layout_item_class', 'masonry-layout-item', 'masonry' ); ?>">
	<?php
	$name = apply_filters( 'cherry_blog_layout_template_name', $format, 'masonry' );

	$prefix = ( ! empty( $parsed_options['template_type'] ) ) ? '-' . $parsed_options['template_type'] : '';
	Cherry_Blog_Template_Loader::get_tmpl( 'layout-masonry' . $prefix, $name );
?>
</article>

<?php
/**
 * Grid Layout type template
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

?>
<div class="<?php echo apply_filters( 'cherry_blog_layout_wrapper_class', 'grid-layout', 'grid' ); ?>" <?php echo Cherry_Blog_Layouts_Tools::wrapper_attrs() ?>>
	<?php
	$parsed_options = Cherry_Blog_Layouts_Data::get_parsed_options();
	echo Cherry_Blog_Layouts_Data::filter_render( $parsed_options['filter_type'] ); ?>

	<?php
		$post_counter = 0;
		$columns = Cherry_Blog_Layouts::get_option( 'blog-layout-columns', 3 );
		while ( have_posts() ) : the_post();
			$template_file = Cherry_Blog_Template_Loader::get_template( 'layout-grid', 'content' );
			include $template_file;
		endwhile;
	?>
</div>
<?php the_posts_pagination(); ?>
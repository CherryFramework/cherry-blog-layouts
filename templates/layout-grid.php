<?php
/**
 * Grid Layout type template
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

Cherry_Blog_Template_Loader::prepare_query();
?>
<div class="<?php echo apply_filters( 'cherry_blog_layout_wrapper_class', 'grid-layout', 'grid' ); ?>" <?php echo Cherry_Blog_Layouts_Tools::wrapper_attrs() ?>>
	<?php echo Cherry_Blog_Layouts_Data::filter_render(); ?>
	<div class="row">
	<?php
		$post_counter = 0;
		$columns = Cherry_Blog_Layouts::get_option( 'blog-layout-columns', 3 );
		while ( have_posts() ) : the_post();
			?>
			<article class="<?php echo Cherry_Blog_Layouts_Tools::item_class( 'grid-layout-item' ); ?>">
			<?php
				$format = get_post_format();

				if ( ! $format ) {
					$format = 'standard';
				}

				$name = apply_filters( 'cherry_blog_layout_template_name', $format, 'grid' );
				Cherry_Blog_Template_Loader::get_tmpl( 'layout', $name );

			?>
			</article>
			<?php
			$post_counter++;
		endwhile;
	?>
	</div>
	<?php the_posts_pagination(); ?>
</div>
<?php
Cherry_Blog_Template_Loader::restore_query();
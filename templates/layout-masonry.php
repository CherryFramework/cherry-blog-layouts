<?php
/**
 * Grid Layout type template
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

//Cherry_Blog_Template_Loader::prepare_query();
?>
<div class="<?php echo apply_filters( 'cherry_blog_layout_wrapper_class', 'masonry-layout', 'masonry' ); ?>"<?php echo Cherry_Blog_Layouts_Tools::wrapper_attrs() ?>>
	<?php echo Cherry_Blog_Layouts_Data::filter_render(); ?>
	<div class="masonry-wrapper">
		<?php
			while ( have_posts() ) : the_post();
				$template_file = Cherry_Blog_Template_Loader::get_template( 'layout-masonry', 'content' );
				include $template_file;
			endwhile;
		?>
	<div class="clear"></div>
	</div>
</div>
<?php the_posts_pagination(); ?>
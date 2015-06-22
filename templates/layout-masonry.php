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
	<div class="masonry-wpapper">
	<?php

		while ( have_posts() ) : the_post();
			?>
				<article class="<?php echo apply_filters( 'cherry_blog_layout_item_class', 'masonry-layout-item', 'masonry' ); ?>">
				<?php

				$format = get_post_format();

				if ( ! $format ) {
					$format = 'standard';
				}

				$name = apply_filters( 'cherry_blog_layout_template_name', $format, 'masonry' );
				Cherry_Blog_Template_Loader::get_tmpl( 'layout', $name );

			?>
			</article>
			<?php
		endwhile;
	?>
	<div class="clear"></div>
	</div>
</div>
<?php
the_posts_pagination();
//Cherry_Blog_Template_Loader::restore_query();
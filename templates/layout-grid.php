<?php
/**
 * Grid Layout type template
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

Cherry_Blog_Template_Loader::prepare_query();
?>
<div class="<?php echo apply_filters( 'cherry_blog_layout_wrapper_class', 'grid-layout', 'grid' ); ?>">
	<div class="row">
	<?php

		while ( have_posts() ) : the_post();
			?>
			<article class="<?php echo Cherry_Blog_Layouts_Tools::item_class( 'grid-layout_item' ); ?>">
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
		endwhile;
	?>
	</div>
	<?php the_posts_pagination(); ?>
</div>
<?php
Cherry_Blog_Template_Loader::restore_query();
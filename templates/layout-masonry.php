<?php
/**
 * Grid Layout type template
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

Cherry_Blog_Template_Loader::prepare_query();
?>
<div class="<?php echo apply_filters( 'cherry_blog_layout_wrapper_class', 'masonry-layout', 'masonry' ); ?>">
	<?php

		while ( have_posts() ) : the_post();

			$format = get_post_format();

			if ( ! $format ) {
				$format = 'standard';
			}

			$name = apply_filters( 'cherry_blog_layout_template_name', $format, 'masonry' );
			Cherry_Blog_Template_Loader::get_tmpl( 'layout', $name );

		endwhile;

		the_posts_pagination();

	?>
</div>
<?php
Cherry_Blog_Template_Loader::restore_query();
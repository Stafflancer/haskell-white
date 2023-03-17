<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\get_main_classes;

get_header();
?>

	<main id="main" class="single-blog-main <?php echo esc_attr( get_main_classes( [] ) ); ?>" role="main">
		<div class="single-blog-details">
			<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content', get_post_type() );

					/*the_post_navigation(
						array(
							'prev_text' => '<span class="nav-subtitle" title="%title">' . esc_html__( 'PREVIOUS', THEME_TEXT_DOMAIN ) . '</span> <span class="nav-title"></span>',
							'next_text' => '<span class="nav-subtitle" title="%title">' . esc_html__( 'NEXT', THEME_TEXT_DOMAIN ) . '</span> <span class="nav-title"></span>',
						)
					);*/
				endwhile; // End of the loop.
			?>
		</div>
	</main><!-- #main -->

<?php
get_footer();

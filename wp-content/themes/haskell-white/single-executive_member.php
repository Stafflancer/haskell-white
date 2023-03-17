<?php
/**
 * The template for displaying all single team member posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\get_main_classes;
use function BopDesign\hwcpa\print_post_thumbnail;
get_header(); ?>
	<main id="main" class="<?php echo esc_attr( get_main_classes( [] ) ); ?>" role="main"><?php
		while ( have_posts() ) :
			the_post(); ?><?php
			get_template_part( 'template-parts/content', get_post_type() ); ?>
			<?php
		endwhile; // End of the loop.
		?>
		<div class="entry-content green-block">
			<?php
				the_content(
					sprintf(
						wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
							esc_html__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', THEME_TEXT_DOMAIN ),
							[
								'span' => [
									'class' => [],
								],
							]
						),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					)
				);

				wp_link_pages(
					[
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', THEME_TEXT_DOMAIN ),
						'after'  => '</div>',
					]
				);
			?>
			</div><!-- .entry-content -->
	</main><!-- #main -->

<?php
get_footer();

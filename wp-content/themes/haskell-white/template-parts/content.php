<?php
/**
 * Template part for displaying posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\print_post_date;
use function BopDesign\hwcpa\print_post_author;
use function BopDesign\hwcpa\print_post_thumbnail;
use function BopDesign\hwcpa\print_entry_footer;
global $current_user;
$author_id = $post->post_author; 
if ( 'post' === get_post_type() ) : ?>
	<section class="rightsideicons"><div class="addtoany">
		<?php echo do_shortcode('[addtoany]'); ?>
	</div></section><?php
endif; ?>
<article id="post-<?php the_ID(); ?>" class="container"><?php
	if ( 'post' === get_post_type() ) : ?>
		<div class="breadcrump">
			<ul class="breadcrump-inner">
				<?php echo bcn_display(); ?>
			</ul>
		</div><?php
	endif; ?>
	<header class="entry-header"><?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta d-flex align-items-center justify-content-between blog-detail">
				<div class="date-author"><span><?php echo get_the_date('M d, Y'); ?></span> | 
				<a href="<?php echo get_author_posts_url($author_id); ?>"><?php the_author(); ?></a></div>
				<div class="reading"><?php echo do_shortcode('[rt_reading_time label="Reading Time:" postfix="minutes" postfix_singular="minute"]'); ?></div>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php print_post_thumbnail(); ?>

	<div class="entry-content">
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

	<!-- <footer class="entry-footer">
		<?php print_entry_footer(); ?>
	</footer> --><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->

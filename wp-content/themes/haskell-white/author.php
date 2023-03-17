<?php
/**
 * The template for displaying author pages.
 *
 * @link       https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package    hwcpa
 * 
 * 
 */

use function BopDesign\hwcpa\print_module;
use function BopDesign\hwcpa\get_main_classes;
use function BopDesign\hwcpa\print_numeric_pagination;
get_header(); ?>
	<main id="main" class="author-devteam <?php echo esc_attr( get_main_classes( [] ) ); ?>" role="main">
		<div class="container"> <div class="hero-banner-common-header text-center">
			<div class="breadcrump">
				<ul class="breadcrump-inner">
					<?php echo bcn_display(); ?>
				</ul>
			</div>
			<h1>All Posts by <?php echo get_the_author(); ?></h1>
		</div>
		<div class="grid-block-author"><?php
		if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();

			print_module( 'card-post', [] );

		endwhile; // End of the loop.
		wp_reset_postdata();

			print_numeric_pagination();
		else :
			get_template_part( 'template-parts/content', 'none' );
		endif;
		?></div>
	</main><!-- #main -->
<?php
get_footer(); ?>
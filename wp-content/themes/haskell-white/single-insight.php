<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\get_main_classes;
use function BopDesign\hwcpa\print_post_thumbnail;
get_header(); 
$content_type = get_the_terms( get_the_ID(), 'content_type' );
$video = get_field('video'); ?>
	<main id="main" class="<?php echo esc_attr( get_main_classes( [] ) ); ?>" role="main">
		<div class="content-block-main">
			<div class="container">
				<div class="max-width-set">
					<div class="breadcrumbs">
					<div class="breadcrump">
						<ul class="breadcrump-inner">
							<?php echo bcn_display(); ?>
						</ul>
					</div>
				</div>
				<?php
				while ( have_posts() ) :
					the_post(); ?>
					<div class="post-title">
						<h1><?php echo get_the_title(); ?></h1>
						<div class="content-inner-box"><?php echo get_the_excerpt(); ?></div>
					</div><?php
					if(!empty($video)){
						// Use preg_match to find iframe src.
						preg_match('/src="(.+?)"/', $video, $matches);
						$src = $matches[1];
						// Add extra parameters to src and replace HTML.
						$params = array(
							'controls'  => 1,
							'muted'        => 0,
							'hd'        => 1,
							'autoplay' => 0,
							'loop' => 0,
						);
						$new_src = add_query_arg($params, $src);
						$iframe = str_replace($src, $new_src, $video);

						// Add extra attributes to iframe HTML.
						$attributes = 'frameborder="0"';
						$iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);
						echo '<div class="video">'.$iframe.'</div></div>';
					}
						       
		 			else{
						if(has_post_thumbnail()){ 
						    print_post_thumbnail();
						}
					} 

					the_post_navigation(
						array(
							'prev_text' => '<span class="nav-subtitle" title="%title">' . esc_html__( 'PREVIOUS', THEME_TEXT_DOMAIN ) . '</span> <span class="nav-title"></span>',
							'next_text' => '<span class="nav-subtitle" title="%title">' . esc_html__( 'NEXT', THEME_TEXT_DOMAIN ) . '</span> <span class="nav-title"></span>',
						)
					);
				endwhile; // End of the loop.
				?></div>	
		</div>
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

	</main><!-- #main -->

<?php
get_footer();

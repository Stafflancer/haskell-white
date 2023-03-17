<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\print_post_date;
use function BopDesign\hwcpa\print_post_author;
use function BopDesign\hwcpa\print_post_thumbnail;
use function BopDesign\hwcpa\print_entry_footer;
?>

<div class="result-post-type">
	<article id="post-<?php the_ID(); ?>" <?php post_class( ); ?>><?php
		if(get_post_type( get_the_ID() ) == 'post'){ 
			$global_post_type = 'Blog';
		} 
		else if(get_post_type( get_the_ID() ) == 'page'){
			$global_post_type = 'Page';
		}
		else if(get_post_type( get_the_ID() ) == 'testimonial'){
			$global_post_type = 'Testimonials';
		} 
		else if(get_post_type( get_the_ID() ) == 'news'){
			$global_post_type = 'News';
		}  
		else if(get_post_type( get_the_ID() ) == 'insight'){
			$global_post_type = 'Insight';
		} 
		else if(get_post_type( get_the_ID() ) == 'executive_member'){
			$global_post_type = 'Executive Members';
		}
		else{
			$global_post_type = 'Post';
		} ?>
		<div class="post-result-data">
			<h5 class="text-color-green"><?php echo $global_post_type; ?></h5>
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<div class="title-description">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
		</div><!-- .entry-header -->


	</article><!-- #post-<?php the_ID(); ?> -->
</div>

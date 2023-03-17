<?php
/**
 * MODULE: Card - News
 * Template part for displaying reusable card in news.
 *
 * Modules are analogous to 'Molecules' in Brad Frost's Atomic Design Methodology.
 *
 * @link https://atomicdesign.bradfrost.com/chapter-2/#molecules
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\get_trimmed_excerpt;
use function BopDesign\hwcpa\print_element;

$publication = get_the_terms( get_the_ID(), 'publication' );
$featured_person = get_the_terms( get_the_ID(), 'featured_person' );

$time_string = '<time class="entry-date published updated" datetime="%1$s">%1$s</time>';
$posted_on   = sprintf( $time_string, esc_attr( get_the_date( 'F d, Y' ) ) );
// TODO: convert to new structure.
?>
<div class="col wow animate__animated animate__fadeInUp">
	<div class="card position-relative h-100 d-flex flex-column text-break rounded overflow-hidden has-background-background-color">
		<div class="card-body flex-grow-1 flex-shrink-1 p-4 p-xl-5"><?php
			if(!empty($publication) || !empty($featured_person)){ 
				$terms_publication = join(' | ', wp_list_pluck($publication, 'name'));
				$terms_featured_person = join(' | ', wp_list_pluck($featured_person, 'name')); 
				$category_devide = ""; 
				if(!empty($terms_publication) && !empty($featured_person)){ 
					$category_devide = ' | ';
				} ?>
				<label><?php echo $terms_publication.$category_devide.$terms_featured_person; ?></label><?php
			} ?>
			<h3 class="card-title h4"><?php the_title(); ?></h3>
			<p class="card-text m-0"><?php echo get_trimmed_excerpt( [ 'post' => get_the_ID(), ] ); ?></p>
		</div>
		<div class="card-info">
			<span class="posted-on"><?php echo $posted_on; ?></span>
			<div class="card-footer d-flex justify-content-end pt-0 px-4 pb-4"><?php
				print_element( 'anchor', [
					'text'  => 'Read More',
					'href'  =>	get_the_permalink(),
					'class' => 'news-card acf-element acf-element-anchor stretched-link',
				] ); ?>	
			</div>
		</div>
	</div>
</div>

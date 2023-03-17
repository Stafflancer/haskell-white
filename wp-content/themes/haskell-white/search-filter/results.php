<?php
/**
 * Search & Filter Pro
 *
 * Sample Results Template
 *
 * @package   Search_Filter
 * @author    Ross Morsali
 * @link      https://searchandfilter.com
 * @copyright 2018 Search & Filter
 *
 * Note: these templates are not full page templates, rather
 * just an encapsulation of the results loop which should
 * be inserted in to other pages by using a shortcode - think
 * of it as a template part
 *
 * This template is an absolute base example showing you what
 * you can do, for more customisation see the WordPress docs
 * and using template tags -
 *
 * http://codex.wordpress.org/Template_Tags
 *
 */

use function BopDesign\hwcpa\print_module;
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $post;
$post_slug = $post->post_name;
$paged = ! empty( $_GET['sf_paged'] ) ? $_GET['sf_paged'] : 1;
if(is_page('executive-team')){
	$terms = get_terms([
    	'taxonomy' => 'position_level',
    	'orderby'  => 'field',
   		'order'    => 'ASC',    
	]);
	foreach($terms as $term){
		$args = array(
			'post_type'=> 'executive_member',
			'post_status' => 'publish',
			'search_filter_id' => 405,
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy'  => 'position_level',
					'field'     => 'term_id',
					'terms'     => $term->term_id,
					)
				),
			);
		$result = new WP_Query( $args );
		if ( $result-> have_posts() ){ ?>
			<div class="filter-list-block">
				<h2><?php echo $term->name; ?></h2>
				<div class="latest-post-main <?php echo 'wrap-'.$post_slug; ?>" id="result_list"><?php
					while ( $result->have_posts() ) {
						$result->the_post();
						print_module( 'card-executive-team', [] );
					} ?>
				</div>
			</div><?php
		}
	}
}
else{
	if ($query->have_posts()) { ?>
		<div class="filter-list-block">
			<div class="latest-post-main <?php echo 'wrap-'.$post_slug; ?>" id="result_list"><?php
				while ($query->have_posts()) {
					$query->the_post(); 
					if(get_post_type( get_the_ID() ) == 'news'){ 
						print_module( 'card-news', [] );
					}
					else if(get_post_type( get_the_ID() ) == 'executive_member'){ 
						print_module( 'card-executive-team', [] );
					}
					else{
						print_module( 'card-post', [] );
					}
				} ?>
			</div><?php
			if($query->max_num_pages > 1)
			{ ?>	
				<div class="pagination-set">
					<div class="pagination-bottom d-flex justify-content-center pagination"><?php
						echo paginate_links( [
							'prev_text' => "previous",
							'next_text' => "next",
							'base'      => site_url() . '%_%',
							'format'    => "?paged=%#%",
							'total'     => $query->max_num_pages,
							'current'   => $paged,
							'mid_size'  => 1,
							'end_size'  => 0,
						] ); ?>
					</div>
				</div><?php
			} ?>
			<?php wp_reset_postdata(); ?>
		</div><?php 
	}
	else { ?>
		<div class="nothing-found">
			<h2>Nothing Found!</h2>
		</div><?php
	} 
} ?>
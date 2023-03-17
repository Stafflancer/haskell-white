<?php
/**
 * MODULE: Latest Reads
 * Display the latest posts by default, this can be changed by passing $args.
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\get_formatted_atts;
use function BopDesign\hwcpa\get_formatted_args;
use function BopDesign\hwcpa\print_module;

$module_defaults = [
	'class'           => [ 'acf-module', 'acf-module-latest-reads', 'my-4' ],
	'exclude_ids'     => [ is_home() ? get_option( 'page_for_posts', true ) : get_the_ID() ],
	'post_type'       => [ 'post' ],
	'number_of_posts' => 3,
];

$module_args = get_formatted_args( $args, $module_defaults );

// Set up element attributes.
$module_atts = get_formatted_atts( [ 'class' ], $module_args );

// Number of posts to query in the database.
$number_of_posts_to_query = 3;

if ( is_int( $module_args['number_of_posts'] ) ) {
	$number_of_posts_to_query = $module_args['number_of_posts'] + 1;
}

$query_args = [
	'post_type'           => $module_args['post_type'],
	'posts_per_page'      => $number_of_posts_to_query,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
];

$recent_posts = new WP_Query( $query_args );

if ( $recent_posts->have_posts() ):
	?>
	<div <?php echo $module_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>>
	<?php
	$posts = 0; // Count the posts displayed, up to $module_args['number_of_posts'].

	if ( $module_args['number_of_posts'] > 3 ) {
		// Turn into slider only if there are enough items.
		wp_enqueue_style( 'swiperjs-style' );
		wp_enqueue_script( 'swiperjs-script' );
		wp_enqueue_script( 'latest-reads-script', get_theme_file_uri( '/blocks/latest-reads/script.js' ), [ 'swiperjs-script' ], rand(), true );

		$animation_delay = 0; // Animation delay variable.
		?>
		<div class="swiper latest-reads-slide">
			<div class="swiper-wrapper">
			<?php
			while ( $recent_posts->have_posts() && $posts < $module_args['number_of_posts'] ) :
				$recent_posts->the_post();
				$current = get_the_ID();

				if ( ! in_array( $current, $module_args['exclude_ids'] ) ) {
					$posts++;
					echo '<div class="swiper-slide wow animate__animated animate__fadeIn" data-wow-delay="' . esc_attr( $animation_delay ) . 's">';
					print_module( 'card-post', [] );
					echo '</div>';
					$animation_delay += 0.25;
				}
			endwhile;
			wp_reset_postdata();
			?>
			</div>
		</div>
		<div class="latest-reads-nav swiper-button-prev"></div>
		<div class="latest-reads-nav swiper-button-next"></div>
	<?php } else {
		$animation_delay = 0; // Animation delay variable.

		echo '<div class="row row-cols-1 row-cols-md-3 g-4">';

		while ( $recent_posts->have_posts() && $posts < $module_args['number_of_posts'] ) :
			$recent_posts->the_post();
			$current = get_the_ID();

			if ( ! in_array( $current, $module_args['exclude_ids'] ) ) {
				$posts++;
				echo '<div class="col wow animate__animated animate__fadeInUp" data-wow-delay="' . esc_attr( $animation_delay ) . 's">';
				print_module( 'card-post', [] );
				echo '</div>';
				$animation_delay += 0.25;
			}
		endwhile;
		wp_reset_postdata();

		echo '</div>';
	}
	?>
	</div>
<?php endif;

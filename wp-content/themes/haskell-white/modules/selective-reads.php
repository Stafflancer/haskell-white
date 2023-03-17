<?php
/**
 * MODULE: Selective Reads
 * Display the posts selected by user.
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\get_formatted_atts;
use function BopDesign\hwcpa\get_formatted_args;
use function BopDesign\hwcpa\print_module;

$module_defaults = [
	'class'          => [ 'acf-module', 'acf-module-latest-reads', 'selective-reads', 'my-4' ],
	'selected_posts' => [],
];

$module_args = get_formatted_args( $args, $module_defaults );

// Set up element attributes.
$module_atts = get_formatted_atts( [ 'class' ], $module_args );

if ( ! empty( $module_args[ 'selected_posts' ] ) ):
	global $post;
	?>
	<div <?php echo $module_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>>
	<?php
	$posts = 0; // Count the posts displayed, up to $module_args['number_of_posts'].

	if ( count( $module_args[ 'selected_posts' ] ) > 3 ) {
		// Turn into slider only if there are enough items.
		wp_enqueue_style( 'swiperjs-style' );
		wp_enqueue_script( 'swiperjs-script' );
		wp_enqueue_script( 'latest-reads-script', get_theme_file_uri( '/blocks/latest-reads/script.js' ), [ 'swiperjs-script' ], rand(), true );

		$animation_delay = 0; // Animation delay variable.
		?>
		<div class="swiper latest-reads">
			<div class="swiper-wrapper">
			<?php
			foreach ( $module_args[ 'selected_posts' ] as $post ) :
				setup_postdata( $post );
				$post_type = $post->post_type;
				$posts++;
				echo '<div class="swiper-slide wow animate__animated animate__fadeIn" data-wow-delay="' . esc_attr( $animation_delay ) . 's">';
				if($post_type == 'news'){
					print_module( 'card-news', [] );
				}
				else{
					print_module( 'card-post', [] );
				}
				echo '</div>';
				$animation_delay += 0.25;
			endforeach;
			wp_reset_postdata();
			?>
			</div>
		</div>
		<div class="latest-reads-nav swiper-button-prev"></div>
		<div class="latest-reads-nav swiper-button-next"></div>
	<?php
	} else {
		$animation_delay = 0; // Animation delay variable.

		echo '<div class="row row-cols-1 row-cols-md-3 g-4">';

		foreach ( $module_args[ 'selected_posts' ] as $post ) :
			setup_postdata( $post );
			$post_type = $post->post_type;
			$posts++;
			echo '<div class="col wow animate__animated animate__fadeInUp" data-wow-delay="' . esc_attr( $animation_delay ) . 's">';
			if($post_type == 'news'){
				print_module( 'card-news', [] );
			}
			else{
				print_module( 'card-post', [] );
			}
			echo '</div>';
			$animation_delay += 0.25;
		endforeach;
		wp_reset_postdata();

		echo '</div>';
	}
	?>
	</div>
<?php endif;

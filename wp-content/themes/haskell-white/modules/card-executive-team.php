<?php
/**
 * MODULE: Card - Executive Members
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
$image = get_post_thumbnail_id();
$image_class = 'card-img-top w-100 h-100 object-cover object-center';
$designation = get_field('designation');
// TODO: convert to new structure.
?>
<div class="col wow animate__animated animate__fadeInUp">
	<div class="overflow-hidden has-background-background-color">
		<div class="team-items">
			<figure class="overflow-hidden" aria-hidden="true" role="img">
				<?php if ( ! empty( $image ) ) : ?>
					<?php echo wp_get_attachment_image( $image, 'medium', array( 'class' => esc_attr( $image_class ) ) ); ?>
				<?php else: ?>
					<img class="<?php echo esc_attr( $image_class ); ?>" src="<?php echo get_theme_file_uri( '/assets/images/placeholder-square.jpg' ); ?>" alt="Image Placeholder" width="764" height="764" aria-hidden="true">
				<?php endif; ?>
			</figure>
			<h3 class="member-title h4"><?php the_title(); ?></h3><?php
			if(!empty($designation)){ ?>
				<label><?php echo $designation; ?></label><?php
			} ?>
		</div>
	</div>
</div>

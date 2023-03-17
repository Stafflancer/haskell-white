<?php
/**
 * Template part for displaying team member content in single-team_member.php.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\print_svg;
use function BopDesign\hwcpa\print_post_thumbnail;
$position_level = get_the_terms( get_the_ID(), 'position_level' );
$designation = get_field('designation');
$email = get_field('email');
$phone = get_field('phone');
$linkedin = get_field('linkedin');
$vcard = get_field('vcard');
$bio = get_field('bio');
$flexible_content = get_field('flexible_content');
$quote = get_field('quote');
$select_created_post = get_field('select_created_post'); ?>
<div class="executive-main-detail">
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-12 col-lg-3">
				<div class="text-center member-center">
						<?php
					if(has_post_thumbnail()){ ?>
						<div class="member-image"> 
							<?php print_post_thumbnail(); ?>
						</div><?php
					} 
					if(!empty($email) && !empty($email['url'])){ ?>
						<a href="<?php echo esc_url($email['url']); ?>" class="text-link"><?php echo $email['title']; ?></a><?php
					} 
					if(!empty($phone) && !empty($phone['url'])){ ?>
						<a href="<?php echo esc_url($phone['url']); ?>" class="text-link"><?php echo $phone['title']; ?></a><div class="flex-column-member"><?php
					} 
					if(!empty($linkedin) && !empty($linkedin['url'])){ ?>
						<a href="<?php echo esc_url($linkedin['url']); ?>" class="linked-in"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a><?php
					} 
					if(!empty($vcard) && !empty($vcard['url'])){ ?>
						<a href="<?php echo esc_url($vcard['url']); ?>" class="common-btn" download><?php echo $vcard['title']; ?></a></div><?php
					} ?>
				</div>
			</div>
			<div class="col-12 col-md-12 col-lg-9">
				<div class="executive-main-data">
					<div class="breadcrump">
						<ul class="breadcrump-inner">
							<?php echo bcn_display(); ?>
						</ul>
					</div>
					<div class="post-title">
						<h1><?php echo get_the_title(); ?></h1><?php
						if(!empty($designation)){ ?>
							<h4><?php echo $designation; ?></h4><?php
						} 
						if(!empty($bio)){ ?>
							<div class="bio">
								<?php echo $bio; ?>
							</div><?php
						} 
						if(!empty($flexible_content)){ ?>
							<div class="flexible-content"><?php
								foreach($flexible_content as $content){
									$left_side_column = $content['left_side_column'];
									$right_side_column = $content['right_side_column'];
									if(!empty($left_side_column)){ ?>
										<div class="left-side-column">
											<?php echo $left_side_column; ?>
										</div><?php 
									}
									if(!empty($right_side_column)){ ?>
										<div class="right-side-column">
											<?php echo $right_side_column; ?>
										</div><?php
									}
								} ?>
							</div><?php
						} ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
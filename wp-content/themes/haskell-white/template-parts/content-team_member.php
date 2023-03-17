<?php
/**
 * Template part for displaying team member content in single-team_member.php.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\print_svg;
$position_level = get_the_terms( get_the_ID(), 'position_level' );
$designation = get_field('designation');
$email = get_field('email');
$phone = get_field('phone');
$linkedin = get_field('linkedin');
$vcard = get_field('vcard');
$bio = get_field('bio');
$flexible_content = get_field('flexible_content'); ?>
<div class="col-12 col-md-8"><?php
				if(has_post_thumbnail()){ ?>
					<div class=""> 
					 <?php print_post_thumbnail(); ?>
					</div><?php
				} 
				if(!empty($email) && !empty($email['url'])){ ?>
					<a href="<?php echo esc_url($email['url']); ?>"><?php echo $email['title']; ?></a><?php
				} 
				if(!empty($phone) && !empty($phone['url'])){ ?>
					<a href="<?php echo esc_url($phone['url']); ?>"><?php echo $phone['title']; ?></a><?php
				} 
				if(!empty($linkedin) && !empty($linkedin['url'])){ ?>
					<a href="<?php echo esc_url($linkedin['url']); ?>"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a><?php
				} 
				if(!empty($vcard) && !empty($vcard['url'])){ ?>
					<a href="<?php echo esc_url($vcard['url']); ?>" class="common-btn" target="_blank"><?php echo $vcard['title']; ?></a><?php
				} ?>
			</div>
			<div class="col-12 col-md-8">
				<div class="breadcrumbs">
					<ul>
						<li><a href="<?php echo get_the_permalink( 104 ); ?>"><?php _e( 'EXECUTIVE TEAM', THEME_TEXT_DOMAIN ); ?></a></li><?php
						if(!empty($position_level)){ 
						$position_level = join(' | ', wp_list_pluck($position_level, 'name')); ?>
						 / <li><?php echo $position_level; ?></li><?php
						} ?>
					</ul>
				</div>
				<div class="post-title">
					<h1><?php echo get_the_title(); ?></h1><?php
					if(!empty($designation)){ ?>
						<h4><?php echo $designation; ?></h4><?php
					} ?>
					<?php echo get_the_content(); ?>
				</div>
			</div>
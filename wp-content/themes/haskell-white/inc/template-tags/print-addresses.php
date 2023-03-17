<?php
/**
 * Display the social links saved in the theme options page.
 *
 * @package hwcpa
 */

namespace BopDesign\hwcpa;

/**
 * Display the addresses saved in the customizer.
 *
 * @author BopDesign
 */
function print_addresses() {
	$contact_group = get_field( 'contact_group', 'option' ); 
	if(!empty($contact_group['addresses'])){ ?>
		<div class="addresses"><?php
			foreach ( $contact_group['addresses'] as $addresses ) {
				$title = $addresses['title'];
				$address = $addresses['address'];
				$phone = $addresses['phone']; ?>
				<div class="address"><?php
					if(!empty($title)){ ?>
						<h4><?php echo $title; ?></h4><?php
					} 
					if(!empty($address)){ ?>
						<div class="address-data">
							<?php echo $address; ?>
						</div><?php
					} 
					if(!empty($phone)){ ?>
						<a href="<?php echo esc_url($phone['url']); ?>"><?php echo $phone['title']; ?></a><?php
					} ?>
				</div><?php 
			} ?>
		</div><?php
	}
}

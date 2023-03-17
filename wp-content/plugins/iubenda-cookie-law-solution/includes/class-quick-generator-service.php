<?php
/**
 * Iubenda quick generator service.
 *
 * @package  Iubenda
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Quick_Generator_Service
 */
class Quick_Generator_Service {

	/**
	 * Quick generator service response
	 *
	 * @var array
	 */
	public $qg_response = array();

	/**
	 * Quick_Generator_Service constructor.
	 */
	public function __construct() {
		$this->qg_response = array_filter( (array) get_option( Iubenda_Settings::IUB_QG_RESPONSE, array() ) );
	}

	/**
	 * Get mapped language on locale.
	 *
	 * @param   string $iub_lang_code  iub_lang_code.
	 *
	 * @return array
	 */
	protected function get_mapped_language_on_local( $iub_lang_code ) {
		$result        = array();
		$iub_lang_code = strtolower( str_replace( '-', '_', $iub_lang_code ) );

		foreach ( iubenda()->languages_locale as $wordpress_locale => $lang_code ) {
			// lower case and replace - with underscore..
			$lower_wordpress_locale = strtolower( str_replace( '-', '_', $wordpress_locale ) );
			// Map after all both codes becomes lower case and underscore..

			// Map en iubenda language to WordPress languages en_us..
			if ( 'en' === $iub_lang_code && 'en_us' === $lower_wordpress_locale ) {
				$result[] = $lang_code;
				continue;
			}

			// Map iubenda language to WordPress languages.
			if ( $iub_lang_code === $lower_wordpress_locale ) {
				$result[] = $lang_code;
				continue;
			}

			// Map pt iubenda language to pt-pt.
			if ( 'pt' === $iub_lang_code && 'pt_pt' === $lower_wordpress_locale ) {
				$result[] = $lang_code;
				continue;
			}

			// Cases iubenda languages without _ mapped to.
			if ( strstr( $lower_wordpress_locale, '_', true ) === $iub_lang_code && 'pt' !== $iub_lang_code ) {
				$result[] = $lang_code;
				continue;
			}
			// Map any XX_ iubenda language to any WordPress languages starts with XX_.
			if ( ( strpos( $iub_lang_code, '_' ) === 0 && strstr( $iub_lang_code, '_', true ) ) && ( strpos( $lower_wordpress_locale, '_' ) === 0 && strstr( $lower_wordpress_locale, '_', true ) ) ) {
				$result[] = $lang_code;
				continue;
			}

			if ( $lower_wordpress_locale === $iub_lang_code ) {
				$result[] = $lang_code;
				continue;
			}
		}

		return $result;
	}

	/**
	 * Quick generator API
	 */
	public function quick_generator_api() {
		iub_verify_ajax_request( 'iub_quick_generator_callback_nonce', 'iub_nonce' );
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$body = iub_array_get( $_POST, 'payload' );
		$user = iub_array_get( $body, 'user' );

		$public_ids       = array();
		$privacy_policies = array();
		$site_id          = null;

		$multi_lang = ( iubenda()->multilang && ! empty( iubenda()->languages ) );

		foreach ( iub_array_get( $body, 'privacy_policies', array() ) ?? array() as $key => $privacy_policy ) {
			// getting site id to save it into Iubenda global option.
			if ( ! $site_id ) {
				$site_id = sanitize_key( iub_array_get( $privacy_policy, 'site_id' ) );
			}

			if ( $multi_lang ) {
				$local_lang_codes = $this->get_mapped_language_on_local( $privacy_policy['lang'] );
				if ( $local_lang_codes ) {
					foreach ( $local_lang_codes as $local_lang_code ) {
						$privacy_policies[ $local_lang_code ] = $privacy_policy;

						// getting public id to save it into Iubenda global option default lang.
						$public_ids[ $local_lang_code ] = sanitize_key( iub_array_get( $privacy_policy, 'public_id' ) );
					}
				}

				// Getting supported local languages intersect with iubenda supported languages.
				$iubenda_intersect_supported_langs = ( new Language_Helper() )->get_local_supported_language();

				// Fallback to default language if no supported local languages intersect with iubenda supported languages.
				if ( empty( $iubenda_intersect_supported_langs ) ) {
					$public_ids[ iubenda()->lang_default ] = sanitize_key( iub_array_get( $privacy_policy, 'public_id' ) );
				}
			} else {
				$privacy_policies['default'] = $privacy_policy;

				// getting public id to save it into Iubenda global option default lang.
				$public_ids['default'] = sanitize_key( iub_array_get( $privacy_policy, 'public_id' ) );
			}
		}

		$configuration = array(
			'website'          => $site_id,
			'user'             => array(
				'id'    => sanitize_key( iub_array_get( $user, 'id' ) ),
				'email' => sanitize_email( iub_array_get( $user, 'email' ) ),
			),
			'privacy_policies' => $privacy_policies,
		);

		update_option( Iubenda_Settings::IUB_QG_RESPONSE, $configuration );
		update_option(
			'iubenda_global_options',
			array(
				'site_id'    => $site_id,
				'public_ids' => $public_ids,
			)
		);

		iubenda()->notice->add_notice( 'iub_legal_documents_generated_success' );

		echo wp_json_encode(
			array(
				'status'   => 'done',
				'redirect' => admin_url( 'admin.php?page=iubenda&view=integrate-setup' ),
			)
		);
		wp_die();
	}

	/**
	 * Integrate setup.
	 */
	public function integrate_setup() {
		iub_verify_ajax_request( 'iub_integrate_setup', 'iub_nonce' );

		// Saving iubenda plugin settings.
		$this->plugin_settings_save_options();

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( (string) iub_array_get( $_POST, 'cookie_law' ) === 'on' ) {
			// Saving CS data with CS function.
			$this->cs_save_options();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( (string) iub_array_get( $_POST, 'privacy_policy' ) === 'on' ) {
			// Saving PP data with PP function.
			$this->pp_save_options();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( (string) iub_array_get( $_POST, 'cookie_law' ) === 'on' || (string) iub_array_get( $_POST, 'privacy_policy' ) === 'on' ) {
			// add notice that`s notice user the integration has been done successfully.
			iubenda()->notice->add_notice( 'iub_products_integrated_success' );
		}

		// Encourage user to verify his account.
		iubenda()->notice->add_notice( 'iub_user_needs_to_verify_his_account' );

		echo wp_json_encode( array( 'status' => 'done' ) );
		wp_die();
	}

	/**
	 * Save public api key
	 */
	public function save_public_api_key() {
		iub_verify_ajax_request( 'iub_public_api_key_nonce', 'iub_nonce' );
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$public_api_key = sanitize_text_field( iub_array_get( $_POST, 'public_api_key' ) );

		if ( empty( $public_api_key ) ) {
			echo wp_json_encode(
				array(
					'status'       => 'error',
					'responseText' => esc_html__( 'invalid public API key', 'iubenda' ),
				)
			);
			wp_die();
		}
		update_option( 'iubenda_consent_solution', array( 'public_api_key' => $public_api_key ) );

		$iubenda_activated_products                             = (array) get_option( 'iubenda_activated_products', array() );
		$iubenda_activated_products['iubenda_consent_solution'] = 'true';
		update_option( 'iubenda_activated_products', $iubenda_activated_products );

		echo wp_json_encode( array( 'status' => 'done' ) );
		wp_die();
	}

	/**
	 * Auto detect forms
	 */
	public function auto_detect_forms() {
		iub_verify_ajax_request( 'iub_auto_detect_forms_nonce', 'iub_nonce' );
		iubenda()->forms->autodetect_forms();

		require_once IUBENDA_PLUGIN_PATH . 'views/partials/auto-detect-forms.php';
		wp_die();

	}

	/**
	 * Add footer
	 */
	public function add_footer() {
		if ( (string) iub_array_get( iubenda()->settings->services, 'pp.status' ) === 'true' && (string) iub_array_get( iubenda()->options['pp'], 'button_position' ) === 'automatic' ) {
			echo esc_html( $this->pp_button() );
		}
		if ( (string) iub_array_get( iubenda()->settings->services, 'tc.status' ) === 'true' && (string) iub_array_get( iubenda()->options['tc'], 'button_position' ) === 'automatic' ) {
			echo esc_html( $this->tc_button() );
		}
	}

	/**
	 * TC button shortcode
	 *
	 * @return array|ArrayAccess|mixed|string|null
	 */
	public function tc_button_shortcode() {
		if ( ( (string) iub_array_get( iubenda()->settings->services, 'tc.status' ) === 'true' ) && ( (string) iub_array_get( iubenda()->options['tc'], 'button_position' ) === 'manual' ) ) {
			return $this->tc_button();
		}

		return '[iub-tc-button]';
	}

	/**
	 * TC button
	 *
	 * @return array|ArrayAccess|mixed|null
	 */
	public function tc_button() {
		if ( iubenda()->multilang && ! empty( iubenda()->lang_current ) ) {
			$code = iub_array_get( iubenda()->options, 'tc.code_' . iubenda()->lang_current ) ?? null;
		} else {
			$code = iub_array_get( iubenda()->options, 'tc.code_default' ) ?? null;
		}

		return $code;
	}

	/**
	 * PP button shortcode
	 *
	 * @return array|ArrayAccess|mixed|string|null
	 */
	public function pp_button_shortcode() {
		if ( (string) iub_array_get( iubenda()->settings->services, 'pp.status' ) === 'true' && (string) iub_array_get( iubenda()->options['pp'], 'button_position' ) !== 'automatic' ) {
			return $this->pp_button();
		}

		return '[iub-pp-button]';
	}

	/**
	 * PP button
	 *
	 * @return array|ArrayAccess|mixed|string|null
	 */
	public function pp_button() {
		$privacy_policy_generator = new Privacy_Policy_Generator();

		if ( iubenda()->multilang && ! empty( iubenda()->lang_current ) ) {
			$code = iub_array_get( iubenda()->options, 'pp.code_' . iubenda()->lang_current ) ?? null;
		} else {
			$code = iub_array_get( iubenda()->options, 'pp.code_default' ) ?? null;
		}

		if ( ! $code ) {
			if ( iubenda()->multilang && ! empty( iubenda()->lang_current ) ) {
				$public_id = iub_array_get( iubenda()->options['global_options'], 'public_ids.' . iubenda()->lang_current );
			} else {
				$public_id = iub_array_get( iubenda()->options['global_options'], 'public_ids.default' );
			}

			$code = $privacy_policy_generator->handle( 'default', $public_id, iub_array_get( iubenda()->options, 'pp.button_style' ) );
		}

		return $code;
	}

	/**
	 * Saving new options from POST to all services
	 */
	public function ajax_save_options() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$iubenda_section_key = (string) iub_array_get( $_POST, 'iubenda_section_key' );
		iub_verify_ajax_request( 'iub_ajax_save_options_nonce', "iub_{$iubenda_section_key}_nonce" );

		$allowed_sections = array(
			'iubenda_consent_solution',
			'iubenda_cookie_law_solution',
			'iubenda_privacy_policy_solution',
			'iubenda_terms_conditions_solution',
		);
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$iubenda_section_name = sanitize_key( strtolower( iub_array_get( $_POST, 'iubenda_section_name' ) ) );

		if ( ! in_array( $iubenda_section_name, $allowed_sections, true ) ) {
			wp_die( esc_html__( 'Sorry, you are not authorized to perform this action.' ), 403 );
		}

		// If section == CS save by CS function.
		if ( 'cs' === $iubenda_section_key ) {
			$this->cs_save_options( false );
		} elseif ( 'plugin-settings' === $iubenda_section_key ) {
			// Elseif section == Plugin-settings save by plugin settings function.
			$this->plugin_settings_save_options( false );
		} else {
			$codes_statues  = array();
			$global_options = iubenda()->options['global_options'];

			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$new_section_options = (array) iub_array_get( $_POST, $iubenda_section_name );
			$old_section_options = iubenda()->options[ $iubenda_section_key ];

			if ( 'iubenda_terms_conditions_solution' === $iubenda_section_name ) {
				foreach ( $new_section_options as $index => $option ) {
					if ( substr( $index, 0, 5 ) === 'code_' ) {
						$parsed_code = iubenda()->parse_tc_pp_configuration( stripslashes_deep( $option ) );

						if ( $parsed_code ) {
							$codes_statues[ "{$iubenda_section_name}_codes" ][]  = true;
							$global_options['public_ids'][ substr( $index, 5 ) ] = sanitize_key( iub_array_get( $parsed_code, 'cookie_policy_id' ) );
						} else {
							$codes_statues[ "{$iubenda_section_name}_codes" ][] = false;
						}
					}
				}

				// validating Embed Codes of TC contains at least one valid code.
				if ( count( array_filter( $codes_statues[ "{$iubenda_section_name}_codes" ] ) ) === 0 ) {
					echo wp_json_encode(
						array(
							'status'       => 'error',
							/* translators: 1: Iubenda section name. */
							'responseText' => sprintf( esc_html__( '( %s ) At least one code must be valid.', 'iubenda' ), $iubenda_section_name ),
						)
					);
					wp_die();
				}
			}

			// Update Privacy policy button style & position.
			if ( 'iubenda_privacy_policy_solution' === $iubenda_section_name ) {
				// Add a widget in the sidebar if the button is positioned automatically.
				if ( 'automatic' === iub_array_get( $new_section_options, 'button_position' ) ) {
					iubenda()->assign_legal_block_or_widget();
				}

				// Merge old old PP options with new options to update codes with new style.
				$old_section_options = $old_section_options ? $this->iub_stripslashes_deep( $old_section_options ) : array();
				$new_section_options = array_merge( $old_section_options, $new_section_options );

				// Update PP codes with new button style.
				$new_section_options = $this->update_button_style( $new_section_options, 'pp' );
			}

			// Update Terms and conditions button style & position.
			if ( 'iubenda_terms_conditions_solution' === $iubenda_section_name ) {
				// Add a widget in the sidebar if the button is positioned automatically.
				if ( 'automatic' === iub_array_get( $new_section_options, 'button_position' ) ) {
					iubenda()->assign_legal_block_or_widget();
				}

				// Update TC codes with new button style.
				$new_section_options = $this->update_button_style( $new_section_options, 'tc' );
			}

			// set the product configured option true.
			$new_section_options['configured'] = 'true';

			$iubenda_activated_products                          = get_option( 'iubenda_activated_products' );
			$iubenda_activated_products[ $iubenda_section_name ] = 'true';
			update_option( 'iubenda_activated_products', $iubenda_activated_products );

			$new_section_options = $this->iub_stripslashes_deep( $new_section_options );
			if ( $old_section_options ) {
				$old_section_options = $this->iub_stripslashes_deep( $old_section_options );
				$new_section_options = array_merge( $old_section_options, $new_section_options );
			}
			update_option( $iubenda_section_name, $new_section_options );

			// update iubenda global options (public_ids).
			update_option( 'iubenda_global_options', $global_options );
		}

		echo wp_json_encode( array( 'status' => 'done' ) );
		wp_die();
	}

	/**
	 * Prepare custom scripts iframes
	 *
	 * @param   array  $data  Array of custom_(scripts/iframes).
	 * @param   string $flag  Scripts/Iframes.
	 *
	 * @return array|ArrayAccess|mixed|null
	 */
	private function prepare_custom_scripts_iframes( $data, $flag ) {
		return array_combine(
			iub_array_get( $data, $flag ),
			iub_array_get( $data, 'type' )
		);
	}

	/**
	 * Iubenda Stripslashes Deep
	 *
	 * @param   array|string $value  array|string.
	 *
	 * @return array|string
	 */
	private function iub_stripslashes_deep( $value ) {
		$value = is_array( $value ) ?
			array_map( 'stripslashes_deep', $value ) :
			stripslashes( $value );

		return $value;
	}

	/**.
	 * Update button style in options
	 *
	 * @param   array  $options      Options.
	 * @param   string $service_key  Iubenda service key.
	 *
	 * @return array
	 */
	public function update_button_style( $options, $service_key ) {
		if ( 'pp' === $service_key ) {
			$privacy_policy_generator = new Privacy_Policy_Generator();
		}

		$new_options  = array();
		$button_style = iub_array_get( $options, 'button_style' );

		foreach ( $options as $key => $index ) {
			$new_options[ $key ] = $index;

			if ( 'code_' === substr( $key, 0, 5 ) ) {
				if ( 'pp' === $service_key ) {
					// Get public_id for this language.
					$public_id = (string) iub_array_get( iubenda()->options['global_options'], 'public_ids.' . substr( $key, 5 ) );
					if ( ( ! $index || empty( $index ) ) && ! empty( $public_id ) ) {
						$index = $privacy_policy_generator->handle( substr( $key, 5 ), $public_id, iubenda()->options['pp'] );
					}
				}

				$new_code            = str_replace(
					array(
						'iubenda-black',
						'iubenda-white',
					),
					"iubenda-{$button_style}",
					$index
				);
				$new_options[ $key ] = $new_code;
			}
		}

		return $new_options;
	}

	/**
	 * Saving Iubenda cookie law solution options
	 *
	 * @param   bool $default_options  If true insert the default options.
	 */
	private function cs_save_options( $default_options = true ) {
		$iubenda_cookie_solution_generator = new Cookie_Solution_Generator();
		$global_options                    = iubenda()->options['global_options'];

		$codes_statues = array();
		$site_id       = null;
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$new_cs_option = (array) iub_array_get( $_POST, 'iubenda_cookie_law_solution' );
		if ( ! $default_options ) {
			// CS plugin general options.
			$new_cs_option['parse'] = isset( $new_cs_option['parse'] );

			if ( isset( $new_cs_option['parser_engine'] ) && in_array( (string) $new_cs_option['parser_engine'], array( 'default', 'new' ), true ) ) {
				$new_cs_option['parser_engine'] = $new_cs_option['parser_engine'];
			} else {
				$new_cs_option['parser_engine'] = iubenda()->defaults['cs']['parser_engine'];
			}

			$new_cs_option['skip_parsing'] = isset( $new_cs_option['skip_parsing'] );
			$new_cs_option['amp_support']  = isset( $new_cs_option['amp_support'] );
		}

		if ( isset( $new_cs_option['custom_scripts'] ) ) {
			$new_cs_option['custom_scripts'] = $this->prepare_custom_scripts_iframes( (array) iub_array_get( $new_cs_option, 'custom_scripts' ), 'script' );

			// Set all selected values Int to not break compatibility with old version.
			$new_cs_option['custom_scripts'] = array_map( 'intval', $new_cs_option['custom_scripts'] );
		} else {
			$new_cs_option['custom_scripts'] = array();
		}

		if ( isset( $new_cs_option['custom_iframes'] ) ) {
			$new_cs_option['custom_iframes'] = $this->prepare_custom_scripts_iframes( iub_array_get( $new_cs_option, 'custom_iframes' ), 'iframe' );

			// Set all selected values Int to not break compatibility with old version.
			$new_cs_option['custom_iframes'] = array_map( 'intval', $new_cs_option['custom_iframes'] );
		} else {
			$new_cs_option['custom_iframes'] = array();
		}

		if ( 'simplified' === iub_array_get( $new_cs_option, 'configuration_type' ) ) {
			// Check explicit accept & reject forced on if TCF is on.
			if ( true === (bool) iub_array_get( $new_cs_option, 'simplified.tcf' ) ) {
				$new_cs_option['simplified']['explicit_accept'] = 'on';
				$new_cs_option['simplified']['explicit_reject'] = 'on';
			}
			$languages = ( new Product_Helper() )->get_languages();
			// loop on iubenda->>language.
			foreach ( $languages as $lang_id => $lang_name ) {
				$privacy_policy_id = sanitize_key( iub_array_get( $global_options, "public_ids.{$lang_id}" ) );

				if ( ! $privacy_policy_id ) {
					continue;
				}

				$site_id = sanitize_key( iub_array_get( iubenda()->options, 'global_options.site_id' ) );

				// Generating CS Simplified code.
				$cs_embed_code = $iubenda_cookie_solution_generator->handle( $lang_id, $site_id, $privacy_policy_id, iub_array_get( $new_cs_option, 'simplified' ) );

				$new_cs_option[ "code_{$lang_id}" ] = $this->iub_stripslashes_deep( $cs_embed_code );

				// generate amp template file if the code is valid.
				if ( $cs_embed_code ) {
					// generate amp template file.
					if ( (bool) iub_array_get( $new_cs_option, 'amp_support' ) ) {
						$template_done[ $lang_id ] = false;

						$template_done[ $lang_id ] = (bool) iubenda()->AMP->generate_amp_template( $cs_embed_code, $lang_id );

						// Check if AMP is checked and the auto generated option is selected.
						if ( 'local' === (string) $new_cs_option['amp_source'] ) {

							if ( false === $template_done[ $lang_id ] ) {
								$this->add_amp_permission_error();
							}
						}

						$new_cs_option['amp_template_done'] = $template_done;

						if ( is_array( $new_cs_option['amp_template'] ) ) {
							foreach ( $new_cs_option['amp_template'] as $lang => $template ) {
								$new_cs_option['amp_template'][ $lang ] = esc_url_raw( $template );
							}
						} else {
							$new_cs_option['amp_template'] = esc_url_raw( $new_cs_option['amp_template'] );
						}
					}
				}
			}
		} elseif ( 'manual' === iub_array_get( $new_cs_option, 'configuration_type' ) ) {
			foreach ( $new_cs_option as $index => $option ) {
				// check code if valid or not.
				if ( substr( $index, 0, 5 ) === 'code_' && ! empty( $option ) ) {
					// Getting data from embed code.
					$parsed_code = iubenda()->parse_configuration( stripslashes_deep( $option ) );

					$new_cs_option[ 'manual_' . "$index" ] = $option;
					$codes_statues[ substr( $index, 5 ) ]  = true;

					// getting cookiePolicyId to save it into Iubenda global option.
					if ( iub_array_get( $parsed_code, 'cookiePolicyId' ) ?? null ) {
						$global_options['public_ids'][ substr( $index, 5 ) ] = iub_array_get( $parsed_code, 'cookiePolicyId' );
					}

					// getting site id to save it into Iubenda global option.
					if ( empty( $site_id ) && iub_array_get( $parsed_code, 'siteId' ) ) {
						$site_id = sanitize_key( iub_array_get( $parsed_code, 'siteId' ) );
					}

					// generate amp template file.
					if ( (bool) iub_array_get( $new_cs_option, 'amp_support' ) ) {
						$lang_id                   = substr( $index, 5 );
						$template_done[ $lang_id ] = false;

						$template_done[ $lang_id ] = (bool) iubenda()->AMP->generate_amp_template( stripslashes_deep( $option ), $lang_id );

						// Check if AMP is checked and the auto generated option is selected.
						if ( 'local' === (string) $new_cs_option['amp_source'] ) {

							if ( false === $template_done[ $lang_id ] ) {
								$this->add_amp_permission_error();
							}
						}

						$new_cs_option['amp_template_done'] = $template_done;

						if ( is_array( $new_cs_option['amp_template'] ) ) {
							foreach ( $new_cs_option['amp_template'] as $lang => $template ) {
								$new_cs_option['amp_template'][ $lang ] = esc_url_raw( $template );
							}
						} else {
							$new_cs_option['amp_template'] = esc_url_raw( $new_cs_option['amp_template'] );
						}
					}
				}
			}
			// validating Embed Codes of CS contains at least one valid code.
			if ( count( array_filter( $codes_statues ) ) === 0 ) {
				echo wp_json_encode(
					array(
						'status'       => 'error',
						'responseText' => esc_html__( '( Iubenda cookie law solution ) At least one code must be valid.', 'iubenda' ),
					)
				);
				wp_die();
			}
		}

		// set the product configured option true.
		$new_cs_option['configured']             = 'true';
		$new_cs_option['us_legislation_handled'] = true;

		// update only cs make it activated service.
		iubenda()->options['activated_products']['iubenda_cookie_law_solution'] = 'true';
		update_option( 'iubenda_activated_products', iubenda()->options['activated_products'] );

		// saving new options merged by old options.
		$new_cs_option  = $this->iub_stripslashes_deep( $new_cs_option );
		$old_cs_options = $this->iub_stripslashes_deep( iubenda()->options['cs'] );

		$new_cs_option = array_merge( $old_cs_options, $new_cs_option );
		update_option( 'iubenda_cookie_law_solution', $new_cs_option );

		// save site ID into Iubenda global option.
		if ( $site_id ) {
			$global_options['site_id'] = $site_id;
		}

		// update iubenda global options (site_id & public_ids).
		iubenda()->options['global_options'] = $global_options;
		update_option( 'iubenda_global_options', $global_options );
	}

	/**
	 * Saving iubenda plugin settings
	 *
	 * @param   bool $default_options  If true insert the default options.
	 */
	private function plugin_settings_save_options( $default_options = true ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$new_options = (array) iub_array_get( $_POST, 'iubenda_plugin_settings', array() );

		if ( ! $default_options ) {
			$new_options['ctype']         = isset( $new_options['ctype'] );
			$new_options['output_feed']   = isset( $new_options['output_feed'] );
			$new_options['output_post']   = isset( $new_options['output_post'] );
			$new_options['deactivation']  = isset( $new_options['deactivation'] );
			$new_options['menu_position'] = $new_options['menu_position'] ?? iubenda()->defaults['cs']['menu_position'];
		}

		$old_cs_options = $this->iub_stripslashes_deep( iubenda()->options['cs'] );
		$new_cs_option  = array_merge( $old_cs_options, $new_options );
		update_option( 'iubenda_cookie_law_solution', $new_cs_option );
	}

	/**
	 * Saving Iubenda privacy policy solution options
	 */
	private function pp_save_options() {
		$privacy_policy_generator = new Privacy_Policy_Generator();
		$global_options           = iubenda()->options['global_options'];

		$languages = ( new Product_Helper() )->get_languages();
		// loop on iubenda->>language.
		foreach ( $languages as $lang_id => $v ) {
			// getting privacy policy id from saved QG response.
			$privacy_policy_id = sanitize_key( iub_array_get( $global_options, "public_ids.{$lang_id}" ) );

			if ( empty( $privacy_policy_id ) ) {
				continue;
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$button_style = sanitize_text_field( iub_array_get( $_POST, 'iubenda_privacy_policy_solution.button_style' ) );
			// Insert PP Simplified code into options.
			$pp_embed_code = $privacy_policy_generator->handle( $lang_id, $privacy_policy_id, $button_style );

			$pp_data[ "code_{$lang_id}" ] = $pp_embed_code;

			// getting public id to save it into Iubenda global option for each lang.
			$global_options['public_ids'][ $lang_id ] = $privacy_policy_id;
		}

		// Add a widget in the sidebar if the button is positioned automatically.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( 'automatic' === iub_array_get( $_POST, 'iubenda_privacy_policy_solution.button_position' ) ) {
			iubenda()->assign_legal_block_or_widget();
		}

		// Set the version of PP service as Simplified.
		$pp_data['version'] = 'Simplified';

		// Set the configured status as true.
		$pp_data['configured'] = 'true';

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		update_option( 'iubenda_privacy_policy_solution', array_merge( $pp_data, iub_array_get( $_POST, 'iubenda_privacy_policy_solution' ) ) );

		// update only pp make it activated service.
		iubenda()->options['activated_products']['iubenda_privacy_policy_solution'] = 'true';
		update_option( 'iubenda_activated_products', iubenda()->options['activated_products'] );

		// update iubenda global options (site_id & public_ids).
		iubenda()->options['global_options'] = $global_options;
		update_option( 'iubenda_global_options', $global_options );
	}

	/**
	 * Add amp permission error
	 */
	public function add_amp_permission_error() {
		iubenda()->notice->add_notice( 'iub_amp_file_creation_fail' );
	}
}

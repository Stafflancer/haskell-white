<?php
/**
 * Consent Database - single form - cons - page.
 *
 * @package  Iubenda
 */

// Including partial header.
require_once IUBENDA_PLUGIN_PATH . 'views/partials/header.php';
?>
<div class="main-box">

	<?php
	// Including partial site-info.
	require_once IUBENDA_PLUGIN_PATH . 'views/partials/site-info.php';

	// Including partial breadcrumb.
	require_once IUBENDA_PLUGIN_PATH . 'views/partials/breadcrumb.php';
	?>

	<form id="postbox-container-2" action="options.php" method="post">
		<?php
		settings_fields( $this->tabs['cons']['key'] );
		do_settings_sections( $this->tabs['cons']['key'] );
		add_settings_section(
			'iubenda_consent_form',
			__( 'Field Mapping', 'iubenda' ),
			array(
				$this,
				'iubenda_consent_form',
			),
			'iubenda_consent_solution'
		);

		$form_id = absint( iub_get_request_parameter( 'form_id', 0 ) );
		$form    = ! empty( $form_id ) ? iubenda()->forms->get_form( $form_id ) : false;

		if ( ! $form ) {
			return;
		}

		$subject       = isset( $form->form_subject ) && is_array( $form->form_subject ) ? array_map( 'sanitize_key', $form->form_subject ) : array();
		$preferences   = isset( $form->form_preferences ) && is_array( $form->form_preferences ) ? array_map( 'sanitize_key', $form->form_preferences ) : array();
		$exclude       = isset( $form->form_exclude ) && is_array( $form->form_exclude ) ? array_map( 'sanitize_key', $form->form_exclude ) : array();
		$legal_notices = isset( $form->form_legal_notices ) && is_array( $form->form_legal_notices ) ? array_map( 'sanitize_key', $form->form_legal_notices ) : array();

		$available_fields = array();

		if ( ! empty( $form->form_fields ) && is_array( $form->form_fields ) ) {
			foreach ( $form->form_fields as $index => $form_field ) {
				if ( is_array( $form_field ) ) {
					$available_fields[] = $form_field['label'] . ' (' . $form_field['type'] . ')';
				} else {
					$available_fields[] = $form_field;
				}
			}
		}
		echo '
        <input type="hidden" value="' . esc_html( $form_id ) . '" name="form_id">
        <div class="px-4 px-lg-5">
            <div class="py-5">
            <div class="d-block d-lg-flex justify-content-between mb-4">
              <div class="d-block d-lg-flex align-items-center text-center text-lg-left">
                <h3 class="m-0 mb-4">' . esc_html__( 'Map fields', 'iubenda' ) . '</h3>
              </div>
              <div class="d-block d-lg-flex align-items-center text-center text-lg-right">
                <div class="misc-pub-section misc-pub-post-status p-0">
                    <label for="status">' . esc_html__( 'Status', 'iubenda' ) . ':</label>
                    <div id="status-select" class="" style="margin: 3px 0 0;">
                        <select id="status" name="status">';
		foreach ( iubenda()->forms->statuses as $name => $label ) {
			echo '<option value="' . esc_html( $name ) . '"' . selected( $form->post_status, $name, true ) . '>' . esc_html( $label ) . '</option>';
		}
		echo '
                        </select>
                    </div>
                </div>
              </div>
            </div>
                
                <h4 class="m-0 mb-2">' . esc_html__( 'Subject fields', 'iubenda' ) . ' <span class="required">(required)</span></h4>
                <p class="mb-3 description">' . esc_html__( 'Subject fields allow you to store a series of identifying values about your individual subjects/users. Please map the subject  field with the corresponding form fields where applicable.', 'iubenda' ) . '</p>
                <table class="widefat mb-4 subject-table">
                    <thead>
                        <td class="label">' . esc_html__( 'Subject field', 'iubenda' ) . '</td>
                        <td class="label">' . esc_html__( 'Form field', 'iubenda' ) . '</td>
                    </thead>
                    <tbody>';
		foreach ( $this->subject_fields as $field_name => $field_label ) {
			$selected = isset( $subject[ $field_name ] ) ? $subject[ $field_name ] : '';
			$none     = 'id' === $field_name ? esc_html__( 'Autogenerated', 'iubenda' ) : esc_html__( 'None', 'iubenda' );

			echo '
                        <tr class="subject-field options-field">
                            <td>' . esc_html( $field_name ) . ' (' . esc_html( $field_label ) . ') </td>
                            <td>
                                <select class="subject-fields-select select-' . esc_html( $field_name ) . '" name="subject[' . esc_html( $field_name ) . ']">
                                    <option value="" ' . selected( $selected, '', false ) . '>' . esc_html( $none ) . '</option>';
			if ( ! empty( $form->form_fields ) ) {
				foreach ( $form->form_fields as $index => $form_field ) {
					// get field data.
					$form_field_value    = is_array( $form_field ) ? $index : $form_field;
					$form_field_label    = is_array( $form_field ) ? $form_field['label'] . ' (' . $form_field['type'] . ')' : $form_field;
					$form_field_selected = is_array( $form_field ) ? $index : $form_field;

					echo '<option value="' . esc_html( $form_field_value ) . '" ' . selected( $selected, $form_field_selected, false ) . '>' . esc_html( $form_field_label ) . '</option>';
				}
			}
			echo '
                                </select>
                            </td>
                        </tr>';
		}
		echo '
                    </tbody>
                </table>
                <br>
                <h4 class="m-0 mb-2">' . esc_html__( 'Preferences fields', 'iubenda' ) . '</h4>
                <p class="mb-3 description">' . esc_html__( 'Preferences fields allow you to store a record of the various opt-ins points at which the user has agreed or given consent, such as fields for agreeing to terms and conditions, newsletter, profiling, etc.', 'iubenda' ) . '</p>
                <table class="widefat mb-4 preferences-table">
                    <thead>
                    <td class="label">' . esc_html__( 'Preferences field', 'iubenda' ) . '</td>
                    <td class="label">' . esc_html__( 'Form field', 'iubenda' ) . '</td>
                    </thead>
                    <tbody>';
		echo '
                    <tr id="preferences-field-template" class="template-field" style="display: none;">
                        <td><input type="text" class="preferences-inputs regular-text" value="" name="preferences[__PREFERENCE_ID__][field]" placeholder="' . esc_html__( 'Enter field name', 'iubenda' ) . '" disabled/></td>
                        <td>
                            <select class="preferences-inputs preferences-fields-select select-' . esc_html( $field_name ) . '" name="preferences[__PREFERENCE_ID__][value]" disabled>';
		if ( ! empty( $form->form_fields ) ) {
			foreach ( $form->form_fields as $index => $form_field ) {
				// get field data.
				$form_field_value = is_array( $form_field ) ? $index : $form_field;
				$form_field_label = is_array( $form_field ) ? $form_field['label'] . ' (' . $form_field['type'] . ')' : $form_field;

				echo '<option value="' . esc_html( $form_field_value ) . '">' . esc_html( $form_field_label ) . '</option>';
			}
		}
		echo '
                            </select>
                            <a href="javascript:void(0)" class="remove-preferences-field button-secondary" title="' . esc_html__( 'Remove', 'iubenda' ) . '">-</a>
                        </td>
                    </tr>';
		if ( $preferences ) {
			$index = 0;

			foreach ( $preferences as $field_name => $field_value ) {
				$selected = isset( $preferences[ $field_name ] ) ? $preferences[ $field_name ] : '';

				echo '
                                <tr class="preferences-field options-field">
                                    <td><input type="text" class="regular-text" value="' . esc_html( $field_name ) . '" name="preferences[' . esc_html( $index ) . '][field]" placeholder="' . esc_html__( 'Enter field name', 'iubenda' ) . '" /></td>
                                    <td>
                                        <select class="preferences-fields-select select-' . esc_html( $field_name ) . '" name="preferences[' . esc_html( $index ) . '][value]">';
				if ( ! empty( $form->form_fields ) ) {
					foreach ( $form->form_fields as $index => $form_field ) {
						// get field data.
						$form_field_value    = is_array( $form_field ) ? $index : $form_field;
						$form_field_label    = is_array( $form_field ) ? $form_field['label'] . ' (' . $form_field['type'] . ')' : $form_field;
						$form_field_selected = is_array( $form_field ) ? $index : $form_field;

						echo '<option value="' . esc_html( $form_field_value ) . '" ' . selected( $selected, $form_field_selected, false ) . '>' . esc_html( $form_field_label ) . '</option>';
					}
				}
				echo '
                                        </select>
                                        <a href="javascript:void(0)" class="remove-preferences-field button-secondary" title="' . esc_html__( 'Remove', 'iubenda' ) . '">-</a>
                                    </td>
                                </tr>';
				$index ++;
			}
		}
		echo '
                    <tr class="submit-field"><td colspan="2"><a href="javascript:void(0)" class="add-preferences-field button-secondary">' . esc_html__( 'Add New Preference', 'iubenda' ) . '</a></td></tr>
                    </tbody>
                </table>
                <br>
                <h4 class="m-0 mb-2">' . esc_html__( 'Exclude fields', 'iubenda' ) . '</h4>
                <p class="mb-3 description">' . esc_html__( 'Exclude fields allow you to create a list of fields that you would like to exclude from your Consent Database recorded proofs (for e.g. password or other fields not related to the consent).', 'iubenda' ) . '</p>
                
                <table class="widefat mb-4 exclude-table">
                    <thead>
                        <td class="label">' . esc_html__( 'Exclude field', 'iubenda' ) . '</td>
                        <td class="label"></td>
                        </thead>
                    <tbody>';
		echo '
                    <tr id="exclude-field-template" class="template-field" style="display: none;">
                        <td>
                            <select class="exclude-fields-select select-' . esc_html( $field_name ) . '" name="exclude[__EXCLUDE_ID__][field]" disabled>';
		if ( ! empty( $form->form_fields ) ) {
			foreach ( $form->form_fields as $index => $form_field ) {
				// get field data.
				$form_field_value = is_array( $form_field ) ? $index : $form_field;
				$form_field_label = is_array( $form_field ) ? $form_field['label'] . ' (' . $form_field['type'] . ')' : $form_field;

				echo '<option value="' . esc_html( $form_field_value ) . '">' . esc_html( $form_field_label ) . '</option>';
			}
		}
		echo '
                            </select>
                            <a href="javascript:void(0)" class="remove-exclude-field button-secondary" title="' . esc_html__( 'Remove', 'iubenda' ) . '">-</a>
                        </td>
                    </tr>';
		if ( $exclude ) {
			$index = 0;
			foreach ( $exclude as $index => $field_name ) {
				$selected = isset( $exclude[ $index ] ) ? $exclude[ $index ] : '';

				echo '
                                            <tr class="exclude-field options-field">
                                                <td>
                                                    <select class="exclude-fields-select select-' . esc_html( $field_name ) . '" name="exclude[' . esc_html( $index ) . '][field]">';
				if ( ! empty( $form->form_fields ) ) {
					foreach ( $form->form_fields as $index => $form_field ) {
						// get field data.
						$form_field_value    = is_array( $form_field ) ? $index : $form_field;
						$form_field_label    = is_array( $form_field ) ? $form_field['label'] . ' (' . $form_field['type'] . ')' : $form_field;
						$form_field_selected = is_array( $form_field ) ? $index : $form_field;

						echo '<option value="' . esc_html( $form_field_value ) . '" ' . selected( $selected, $form_field_selected, false ) . '>' . esc_html( $form_field_label ) . '</option>';
					}
				}
				echo '
                                                    </select>
                                                    <a href="javascript:void(0)" class="remove-exclude-field button-secondary" title="' . esc_html__( 'Remove', 'iubenda' ) . '">-</a>
                                                </td>
                                                <td></td>
                                            </tr>';

				$index ++;
			}
		}
		echo '
                    <tr class="submit-field"><td colspan="2"><a href="javascript:void(0)" class="add-exclude-field button-secondary">' . esc_html__( 'Add New Exclude', 'iubenda' ) . '</a></td></tr>
                    </tbody>
                </table>
                
                <br>
                <h4 class="m-0 mb-2">' . esc_html__( 'Legal documents', 'iubenda' ) . '</h4>
                <p class="mb-3 description">' . esc_html__( 'In general, it\'s important that you declare which legal documents are being agreed upon when each consent is collected. However, if you use iubenda for your legal documents, it is *required*  that you identify the documents by selecting them here.', 'iubenda' ) . '</p>                
                <table class="widefat mb-4 legal_notices-table">
                    <thead>
                    <td class="label">' . esc_html__( 'Identifier', 'iubenda' ) . '</td>
                    <td class="label"></td>
                    </thead>
                    <tbody>';

		// default identifiers.
		foreach ( $this->legal_notices as $index => $field_name ) {
			echo '
                    <tr class="legal_notices-field default-field">
                        <td>' . ( esc_html( $index ) === 0 ? '<p class="description">' . esc_html__( 'Please select each legal document available on your site.', 'iubenda' ) . '</p>' : '' ) . '<label for="legal_notices-default-field=' . esc_html( $index ) . '"><input id="legal_notices-default-field=' . esc_html( $index ) . '" type="checkbox" value="' . esc_html( $field_name ) . '" name="legal_notices[' . esc_html( $index ) . '][field]"' . checked( in_array( $field_name, $legal_notices, true ), true, false ) . 'placeholder="' . esc_html__( 'Enter field name', 'iubenda' ) . '" />' . esc_html( $field_name ) . '</label></td>
                        <td></td>
                    </tr>';
		}

		$index ++;

		// custom identifiers.
		echo '
                    <tr id="legal_notices-field-template" class="template-field" style="display: none;">
                        <td><input type="text" value="" name="legal_notices[__LEGAL_NOTICE_ID__][field]" placeholder="' . esc_html__( 'Enter field name', 'iubenda' ) . '"  class="regular-text legal-notices-inputs" disabled /> <a href="javascript:void(0)" class="remove-legal_notices-field button-secondary" title="' . esc_html__( 'Remove', 'iubenda' ) . '">-</a></td>
                        <td></td>
                    </tr>';

		echo '
                    <tr>
                        <td colspan="2"><p class="description" style="margin-bottom: 0;">' . esc_html__( 'Alternatively, you may add your own custom document identifiers.', 'iubenda' ) . '</p></td>
                    </tr>';

		if ( $legal_notices ) {
			foreach ( $legal_notices as $field_name ) {
				if ( in_array( $field_name, $this->legal_notices, true ) ) {
					continue;
				}

				echo '
                            <tr class="legal_notices-field options-field">
                                <td><input type="text" class="regular-text" value="' . esc_html( $field_name ) . '" name="legal_notices[' . esc_html( $index ) . '][field]" placeholder="' . esc_html__( 'Enter field name', 'iubenda' ) . '" /> <a href="javascript:void(0)" class="remove-legal_notices-field button-secondary" title="' . esc_html__( 'Remove', 'iubenda' ) . '">-</a></td>
                                <td></td>
                            </tr>';

				$index ++;
			}
		}
		echo '
                    <tr class="submit-field"><td colspan="2"><a href="javascript:void(0)" class="add-legal_notices-field button-secondary">' . esc_html__( 'Add New Document', 'iubenda' ) . '</a></td></tr>';
		echo '
                    </tbody>
                </table>
            </div>
        </div>';
		?>
		<hr>
		<div class="p-4 d-flex justify-content-end">
			<input class="btn btn-gray-lighter btn-sm mr-2" type="button" value="<?php esc_html_e( 'Cancel', 'iubenda' ); ?>" onclick="window.location.href = '<?php echo esc_url( add_query_arg( array( 'view' => 'cons-configuration' ), iubenda()->base_url ) ); ?>'"/>
			<button type="submit" class="btn btn-green-primary btn-sm"
					value="Save" name="save">
				<span class="button__text"><?php esc_html_e( 'Save settings', 'iubenda' ); ?></span>
			</button>
		</div>
	</form>
</div>

<?php
// Including partial footer.
require_once IUBENDA_PLUGIN_PATH . 'views/partials/footer.php';
?>

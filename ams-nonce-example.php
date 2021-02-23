<?php

add_action( 'admin_menu', 'ams_nonce_example_menu'   );
add_action( 'admin_init', 'ams_nonce_example_verify' );

function ams_nonce_example_menu() {

	add_menu_page(
		'Nonce Example',
		'Nonce Example',
		'manage_options',
		'ams-nonce-example',
		'ams_nonce_example_template'
	);
}

function ams_nonce_example_verify() {

	// Bail if no nonce field.
	if ( ! isset( $_POST['ams_nonce_name'] ) ) {
		return;
	}

	// Display error and die if not verified.
	if ( ! wp_verify_nonce( $_POST['ams_nonce_name'], 'ams_nonce_action' ) ) {
		wp_die( 'Your nonce could not be verified.' );
	}

	// Sanitize and update the option if it's set.
	if ( isset( $_POST['ams_nonce_example'] ) ) {
		update_option(
			'ams_nonce_example',
			wp_strip_all_tags( $_POST['ams_nonce_example'] )
		);
	}
}

function ams_nonce_example_template() { ?>

	<div class="wrap">
		<h1 class="wp-heading-inline">Nonce Example</h1>

		<?php $value = get_option( 'ams_nonce_example' ); ?>

		<form method="post" action="">

			<?php wp_nonce_field( 'ams_nonce_action', 'ams_nonce_name' ); ?>

			<p>
				<label>
					Enter your name:
					<input type="text" name="ams_nonce_example" value="<?php echo esc_attr( $value ); ?>" />
				</label>
			</p>

			<?php submit_button( 'Submit', 'primary' ); ?>
		</form>
	</div>
<?php }

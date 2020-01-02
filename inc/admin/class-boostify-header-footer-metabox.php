<?php


defined( 'ABSPATH' ) || exit;

/**
 * Main Boostify Header Footer Metabox Class
 *
 * @class Boostify_Header_Footer_Metabox
 */
if ( ! class_exists( 'Boostify_Header_Footer_Metabox' ) ) {
	class Boostify_Header_Footer_Metabox {

		/**
		 * Boostify Header Footer Metabox Constructor.
		 */
		public function __construct() {
			$this->hooks();
		}

		public function hooks() {
			add_action( 'add_meta_boxes', array( $this, 'pagesetting_meta_box' ) );
			add_action( 'save_post', array( $this, 'pagesetting_save' ) );
			add_action( 'add_meta_boxes', array( $this, 'hf_post_settings' ) );
			add_action( 'save_post', array( $this, 'hf_post_settings_save' ) );
		}

		public function pagesetting_meta_box() {
			add_meta_box( 'ht_hf_setting', 'Template Settings', array( $this, 'ht_hfsetting_output' ), 'btf_builder', 'side' );
		}

		public function ht_hfsetting_output( $post ) {
			$type          = get_post_meta( $post->ID, 'bhf_type', true );
			$select_footer = '';
			$select_header = '';
			// Check Type Selected
			if ( 'footer' == $type ) {
				$select_footer = ' selected';
			}

			if ( 'header' == $type ) {
				$select_header = ' selected';
			}

			wp_nonce_field( 'boostify_hf_action', 'boostify_hf' );
			?>

			<div class="form-meta-footer">

				<!-- Choose Header or Footer -->
				<div class="input-wrapper">
					<label for="container"><?php echo esc_html__( 'Type of Template', 'boostify' ); ?></label>
					<select name="bhf_type" id="container">
						<option value="header"<?php echo esc_attr( $select_header ); ?>><?php echo esc_html__( 'Header', 'boostify' ); ?></option>
						<option value="footer"<?php echo esc_attr( $select_footer ); ?>><?php echo esc_html__( 'Footer', 'boostify' ); ?></option>
					</select>
				</div>

			</div>
			<?php
		}

		public function pagesetting_save( $post_id ) {
			$nonce_name   = isset( $_POST['boostify_hf'] ) ? sanitize_text_field( $_POST['boostify_hf'] ) : '';
			$nonce_action = 'boostify_hf_action';

			if ( ! isset( $nonce_name ) ) {
				return;
			}
			// Check if a nonce is valid.
			if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
				return;
			}

			// Check if the user has permissions to save data.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			// Check if it's not an autosave.
			if ( wp_is_post_autosave( $post_id ) ) {
				return;
			}

			// Check if it's not a revision.
			if ( wp_is_post_revision( $post_id ) ) {
				return;
			}

			$type = ( isset( $_POST['bhf_type'] ) ) ? sanitize_text_field( $_POST['bhf_type'] ) : 'show';

			update_post_meta(
				$post_id,
				'bhf_type',
				$type
			);
		}

		// For choose header footer Post And page

		public function hf_post_settings() {
			add_meta_box( 'hf_post_setting', 'Header Footer Template', array( $this, 'hf_setting_output' ), array( 'post', 'page' ), 'side' );
		}

		public function hf_setting_output( $post ) {
			$header_display = get_post_meta( $post->ID, 'bhf_display_header', true );
			$footer_display = get_post_meta( $post->ID, 'bhf_display_footer', true );
			$header_posts   = boostify_hf_header_option();
			$footer_posts   = boostify_hf_footer_option();

			wp_nonce_field( 'bhf_action', 'bhf' );
			?>

			<div class="form-meta-footer">

				<!-- Choose Header or Footer -->

				<div class="hf-input-wrapper">
					<label for="bhf-display-header"><?php echo esc_html__( 'Header', 'boostify' ); ?></label>
					<select name="bhf_display_header" id="bhf-display-header">
						<?php
						foreach ( $header_posts as $index => $header ) {

							?>
							<option value="<?php echo esc_attr( $index ); ?>"<?php echo esc_attr( ( $index == $header_display ) ? ' selected' : '' ); ?> ><?php echo esc_html( $header ); ?></option>
							<?php
						}

						?>
					</select>
				</div>

				<div class="hf-input-wrapper">
					<label for="bhf_display_footer"><?php echo esc_html__( 'Footer', 'boostify' ); ?></label>
					<select name="bhf_display_footer" id="bhf_display_footer">
						<?php
						foreach ( $footer_posts as $index => $footer ) {
							?>
							<option value="<?php echo esc_attr( $index ); ?>"<?php echo esc_attr( ( $index == $footer_display ) ? ' selected' : '' ); ?> ><?php echo esc_html( $footer ); ?></option>
							<?php
						}

						?>
					</select>
				</div>

			</div>
			<?php
		}

		public function hf_post_settings_save( $post_id ) {
			$nonce_name   = isset( $_POST['bhf'] ) ? $_POST['bhf'] : '';
			$nonce_action = 'bhf_action';

			if ( ! isset( $nonce_name ) ) {
				return;
			}
			// Check if a nonce is valid.
			if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
				return;
			}

			// Check if the user has permissions to save data.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			// Check if it's not an autosave.
			if ( wp_is_post_autosave( $post_id ) ) {
				return;
			}

			// Check if it's not a revision.
			if ( wp_is_post_revision( $post_id ) ) {
				return;
			}

			$display_header = ( isset( $_POST['bhf_display_header'] ) ) ? sanitize_text_field( $_POST['bhf_display_header'] ) : '';
			update_post_meta(
				$post_id,
				'bhf_display_header',
				$display_header
			);

			$display_footer = ( isset( $_POST['bhf_display_footer'] ) ) ? sanitize_text_field( $_POST['bhf_display_footer'] ) : '';
			update_post_meta(
				$post_id,
				'bhf_display_footer',
				$display_footer
			);
		}

	}
}

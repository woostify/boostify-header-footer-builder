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
		}

		public function pagesetting_meta_box() {
			add_meta_box( 'ht_hf_setting', 'Page Settings', array( $this, 'ht_hfsetting_output' ), 'btf_builder' );
		}

		public function ht_hfsetting_output( $post ) {
			$type          = get_post_meta( $post->ID, 'hf_type', true );
			$display       = get_post_meta( $post->ID, 'hf_display', true );
			$select_footer = '';
			$select_header = '';
			// Check Type Selected
			if ( 'footer' == $type ) {
				$select_footer = ' selected';
			}

			if ( 'header' == $type ) {
				$select_header = ' selected';
			}

			$args = [
				'post_type'           => 'page',
				'posts_per_page'      => -1,
				'ignore_sticky_posts' => 1,
			];

			$options = new WP_Query( $args );


			wp_nonce_field( 'boostify_hf_action', 'boostify_hf' );
			?>

			<div class="form-meta-footer">

				<!-- Choose Header or Footer -->
				<div class="input-wrapper">
					<label for="container"><?php echo esc_html__( 'Type of Template', 'boostify' ); ?></label>
					<select name="hf_type" id="container">
						<option value="header"<?php echo esc_attr( $select_header ); ?>><?php echo esc_html__( 'Header', 'boostify' ); ?></option>
						<option value="footer"<?php echo esc_attr( $select_footer ); ?>><?php echo esc_html__( 'Footer', 'boostify' ); ?></option>
					</select>
				</div>


				<div class="input-wrapper">
					<label for="header-transparent"><?php echo esc_html__( 'Display On', 'boostify' ); ?></label>
					<select name="hf_display" id="header-transparent">
						<option value="0" ><?php echo esc_html__( 'All Page', 'boostify' ); ?></option>
						<?php
							while ( $options->have_posts() ) {
								$options->the_post();
								?>
								<option value="<?php echo esc_attr( get_the_ID() ); ?>"<?php echo esc_attr( ( get_the_ID() == $display ) ? ' selected' : '' ); ?> ><?php echo esc_html( get_the_title() ); ?></option>
								<?php
							}

							wp_reset_postdata();
						?>
					</select>
				</div>

			</div>
			<?php
		}

		public function pagesetting_save( $post_id ) {
			$nonce_name   = isset( $_POST['boostify_hf'] ) ? $_POST['boostify_hf'] : '';
			$nonce_action = 'boostify_hf_action';

			if ( ! isset( $nonce_name ) )
				return;
			// Check if a nonce is valid.
			if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
				return;

			// Check if the user has permissions to save data.
			if ( ! current_user_can( 'edit_post', $post_id ) )
				return;

			// Check if it's not an autosave.
			if ( wp_is_post_autosave( $post_id ) )
				return;

			// Check if it's not a revision.
			if ( wp_is_post_revision( $post_id ) )
				return;

			$type = ( isset($_POST['hf_type']) ) ? sanitize_text_field( $_POST['hf_type'] ) : 'show';

			update_post_meta(
				$post_id,
				'hf_type',
				$type
			);

			$display = ( isset( $_POST['hf_display'] ) ) ? sanitize_text_field( $_POST['hf_display'] ) : 'show';
			update_post_meta(
				$post_id,
				'hf_display',
				$display
			);

		}

	}
}

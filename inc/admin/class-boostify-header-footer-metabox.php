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
			add_action( 'wp_ajax_boostify_hf_post_admin', array( $this, 'boostify_hf_post_admin' ) );
			add_action( 'wp_ajax_nopriv_boostify_hf_post_admin', array( $this, 'boostify_hf_post_admin' ) );
		}

		public function pagesetting_meta_box() {
			add_meta_box( 'ht_hf_setting', 'Template Settings', array( $this, 'ht_hfsetting_output' ), 'btf_builder', 'side' );
		}

		public function ht_hfsetting_output( $post ) {
			$type          = get_post_meta( $post->ID, 'bhf_type', true );
			$display       = get_post_meta( $post->ID, 'bhf_display', true );
			$posts         = get_post_meta( $post->ID, 'bhf_post', true );
			$post_type     = get_post_meta( $post->ID, 'bhf_post_type', true );
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

				<?php $this->hf_display( $post ); ?>

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

			// Type of Template Builder
			$type = ( isset( $_POST['bhf_type'] ) ) ? sanitize_text_field( $_POST['bhf_type'] ) : 'header';

			update_post_meta(
				$post_id,
				'bhf_type',
				$type
			);

			// Display On
			$display = ( isset( $_POST['bhf_display'] ) ) ? sanitize_text_field( $_POST['bhf_display'] ) : '';

			update_post_meta(
				$post_id,
				'bhf_display',
				$display
			);

			// Post
			$metabox = $_POST;
			$post = ( isset( $_POST['bhf_post'] ) ) ? sanitize_text_field( $_POST['bhf_post'] ) : '';

			update_post_meta(
				$post_id,
				'bhf_post',
				$post
			);


			// Post Type
			$post_type = ( isset( $_POST['bhf_post_type'] ) ) ? sanitize_text_field( $_POST['bhf_post_type'] ) : '';

			update_post_meta(
				$post_id,
				'bhf_post_type',
				$post_type
			);

		}


		public function hf_display( $post ) {
			$post_types       = get_post_types();
			$post_types_unset = [
				'attachment'          => 'attachment',
				'revision'            => 'revision',
				'nav_menu_item'       => 'nav_menu_item',
				'custom_css'          => 'custom_css',
				'customize_changeset' => 'customize_changeset',
				'oembed_cache'        => 'oembed_cache',
				'user_request'        => 'user_request',
				'wp_block'            => 'wp_block',
				'elementor_library'   => 'elementor_library',
				'btf_builder'         => 'btf_builder',
				'elementor-hf'        => 'elementor-hf',
				'elementor_font'      => 'elementor_font',
				'elementor_icons'     => 'elementor_icons',
				'wpforms'             => 'wpforms',
				'wpforms_log'         => 'wpforms_log',
				'acf-field-group'     => 'acf-field-group',
				'acf-field'           => 'acf-field',
				'booked_appointments' => 'booked_appointments',
				'wpcf7_contact_form'  => 'wpcf7_contact_form',
			];
			$diff             = array_diff( $post_types, $post_types_unset );
			$default          = [
				'all'     => 'All Page',
				'blog'    => 'Blog Page',
				'archive' => 'Archive Page',
				'search'  => 'Search Page',
			];
			$options          = array_merge( $default, $diff );
			$display          = get_post_meta( $post->ID, 'bhf_display', true );
			$post_id          = get_post_meta( $post->ID, 'bhf_post', true );
			$post_type        = get_post_meta( $post->ID, 'bhf_post_type', true );

			?>
				<div class="input-wrapper">
					<div class="parent-item">
						<label for="display-on"><?php echo esc_html__( 'Display On', 'boostify' ); ?></label>
						<select name="bhf_display" id="display-on">
							<?php
							foreach ( $options as $key => $option ) :
								$selected = ( $key == $display ) ? 'selected' : '';
								?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $option ); ?></option>
							<?php endforeach ?>
						</select>
					</div>

					<div class="child-item">
						<div class="input-item-wrapper">
							<?php
							if ( ! empty( $post_id ) && ! empty( $post_type ) ) :
								$list_post = $this->get_posts( $post_type );
								?>
								<select name="bhf_post" id="post">
									<option value="all"><?php echo esc_html( 'All', 'boostify' ); ?></option>
									<?php
									if ( $list_post->have_posts() ) :
										while ( $list_post->have_posts() ) {
											$list_post->the_post();
											$select = ( get_the_ID() == $post_id ) ? 'selected' : '';
											?>
											<option value="<?php echo esc_attr( get_the_ID() ); ?>" <?php echo esc_attr( $select ); ?>>
												<?php echo esc_html( get_the_title() ); ?>
											</option>
											<?php
										}
										wp_reset_postdata();
									endif;
									?>
								</select>
								<input type="hidden" name="bhf_post_type" value="<?php echo esc_attr( $post_type ); ?>">
								<?php
							endif;
							?>
						</div>
					</div>
				</div>
			<?php

		}

		public function boostify_hf_post_admin() {
			check_ajax_referer( 'ht_hf_nonce' );
			$post_type = $_POST['post_type'];
			$posts     = $this->get_posts( $post_type );

			if ( 'all' != $post_type && 'archive' != $post_type && 'search' != $post_type && 'blog' != $post_type ) :
				?>
				<div class="hf-input-item-wrapper">
					<select name="bhf_post" id="post">
						<option value="all"><?php echo esc_html( 'All', 'boostify' ); ?></option>
						<?php
						if ( $posts->have_posts() ) :
							while ( $posts->have_posts() ) {
								$posts->the_post();
								?>
								<option value="<?php echo esc_attr( get_the_ID() ); ?>">
									<?php echo esc_html( get_the_title() ); ?>
								</option>
								<?php
							}
							wp_reset_postdata();
						endif;
						?>
					</select>
					<input type="hidden" name="bhf_post_type" value="<?php echo esc_attr( $post_type ); ?>">
				</div>
				<?php
			endif;

			die();
		}

		public function get_posts( $post_type ) {
			$args  = [
				'post_type'      => $post_type,
				'orderby'        => 'name',
				'order'          => 'ASC',
				'posts_per_page' => -1,
			];
			$posts = new WP_Query( $args );

			return $posts;
		}

	}
}

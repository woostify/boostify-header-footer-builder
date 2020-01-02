<?php


defined( 'ABSPATH' ) || exit;

/**
 * Main Boostify Header Footer Admin Class
 *
 * @class Boostify_Header_Footer_Admin
 */
if ( ! class_exists( 'Boostify_Header_Footer_Admin' ) ) {
	class Boostify_Header_Footer_Admin {


		private static $_instance;


		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Boostify Header Footer Admin Constructor.
		 */
		public function __construct() {
			$this->hooks();
		}

		public function hooks() {
			add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_style' ) );
			add_action( 'admin_menu', array( $this, 'admin_register_menu' ), 62 );
			add_action( 'admin_init', array( $this, 'register_settings' ) );
			add_action( 'rest_api_init', array( $this, 'register_settings' ) );
		}

		public function load_admin_style() {
			wp_enqueue_style(
				'wt-admin',
				HT_HF_URL . 'assets/css/admin/admin.css',
				array(),
				HT_HF_VER
			);
		}

		public function admin_register_menu() {
			// Filter to remove Admin menu.

			add_submenu_page(
				'edit.php?post_type=btf_builder',
				'Header Footer Settings',
				'Header Footer Settings',
				'manage_options',
				'bhf-settings',
				array( $this, 'setting_screen' )
			);
		}

		public function setting_screen() {
			$header_default = get_option( 'bhf_default_header' );
			$footer_default = get_option( 'bhf_default_footer' );
			$header_posts = boostify_hf_header_option();
			$footer_posts = boostify_hf_footer_option();

			?>
			<div class="ht-hf-setting-page">
				<div class="header">
					<h1 class="title"><?php echo esc_html__( 'Settings', 'boostify' ); ?></h1>
				</div>
				<div class="setting-content">
					<div class="form-setting">

						<form method="post" action="options.php">
							<?php settings_fields( 'bhf-settings' ); ?>
							<table>
								<tr valign="middle">
									<th scope="row">
										<label for="bhf_default_header"><?php echo esc_html__( 'Default Header', 'boostify' ); ?></label></th>

									<td>
										<select name="bhf_default_header" id="bhf_default_header">
											<?php if ( ! empty( $header_default ) ) : ?>
												<option value="<?php echo esc_attr( $header_default ); ?>">
													<?php echo esc_html( $header_posts[ $header_default ] ); ?>
												</option>
											<?php endif ?>
											<?php
											foreach ( $header_posts as $id => $template ) :
												if ( $id != $header_default ) :
												?>
													<option value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $template ); ?></option>
													<?php
												endif;

											endforeach;
											?>
										</select>
									</td>
								</tr>

								<tr valign="middle">
									<th scope="row">
										<label for="bhf_default_footer"><?php echo esc_html__( 'Default Footer', 'boostify' ); ?></label></th>
									<td>

										<select name="bhf_default_footer" id="bhf_default_footer">
											<?php if ( ! empty( $footer_default ) ) : ?>
												<option value="<?php echo esc_attr( $footer_default ); ?>">
													<?php echo esc_html( $footer_posts[ $footer_default ] ); ?>
												</option>
											<?php endif ?>
											<?php
											foreach ( $footer_posts as $id => $template ) :
												if ( $id != $footer_default ) :
													?>
												<option value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $template ); ?></option>
												<?php
												endif;

											endforeach;
											?>
										</select>
									</td>
								</tr>

							</table>

							<?php submit_button(); ?>
						</form>

					</div>
				</div>
			</div>
			<?php
		}

		public function register_settings() {
			register_setting(
				'bhf-settings',
				'bhf_default_header',
				array(
					'type'              => 'string',
					'show_in_rest'      => true,
					'sanitize_callback' => 'sanitize_text_field',
				)
			);

			register_setting(
				'bhf-settings',
				'bhf_default_footer',
				array(
					'type'              => 'string',
					'show_in_rest'      => true,
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
		}
	}

	Boostify_Header_Footer_Admin::instance();
}

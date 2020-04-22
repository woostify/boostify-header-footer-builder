<?php

namespace Boostify_Header_Footer;

defined( 'ABSPATH' ) || exit;

/**
 * Main Boostify Header Footer Admin Class
 *
 * @class Boostify_Header_Footer_Admin
 */

class Admin {

	private static $instance;

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Boostify Header Footer Admin Constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_style' ) );
		add_filter( 'manage_btf_builder_posts_columns', array( $this, 'columns_head' ) );
		add_action( 'manage_btf_builder_posts_custom_column', array( $this, 'columns_content' ), 10, 2 );
		add_action( 'admin_footer', array( $this, 'lightbox' ) );
	}

	public function load_admin_style() {

		wp_enqueue_style(
			'boostify-hf-admin',
			BOOSTIFY_HEADER_FOOTER_URL . 'assets/css/admin/admin.css',
			array(),
			BOOSTIFY_HEADER_FOOTER_VER
		);

		wp_enqueue_style(
			'ionicons',
			BOOSTIFY_HEADER_FOOTER_URL . '/assets/css/ionicons.css',
			array(),
			BOOSTIFY_HEADER_FOOTER_VER
		);

		wp_enqueue_script(
			'boostify-hf-admin',
			BOOSTIFY_HEADER_FOOTER_URL . 'assets/js/admin' . boostify_header_footer_suffix() . '.js',
			array( 'jquery', 'suggest' ),
			BOOSTIFY_HEADER_FOOTER_VER,
			true
		);

		$admin_vars = array(
			'url'   => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'ht_hf_nonce' ),
		);

		wp_localize_script(
			'boostify-hf-admin',
			'admin',
			$admin_vars
		);
	}

	public function columns_head( $columns ) {
		$date_column = $columns['date'];

		unset( $columns['date'] );
		$columns['type']      = __( 'Type', 'boostify' );
		$columns['shortcode'] = __( 'Shortcode', 'boostify' );
		$columns['date']      = $date_column;

		return $columns;
	}

	// SHOW THE FEATURED IMAGE
	public function columns_content( $column_name, $post_id ) {
		$type = get_post_meta( $post_id, 'bhf_type', true );
		switch ( $column_name ) {
			case 'shortcode':
				ob_start();
				?>
				<span class="bhf-shortcode-col-wrap">
					<input type="text" readonly="readonly" value="[bhf id='<?php echo esc_attr( $post_id ); ?>' type='<?php echo esc_attr( $type ); ?>']" class="bhf-large-text code">
				</span>

				<?php

				ob_get_contents();
				break;

			case 'type':
				ob_start();
				?>
				<span class="bhf-type"><?php echo esc_html( $type ); ?></span>
				<?php
				ob_get_contents();
				break;
		}
	}

	public function lightbox() {
		$object    = get_current_screen();
		$post_type = $object->post_type;
		$action    = $object->action;
		if ( 'btf_builder' === $post_type && 'add' === $action ) {
			?>
			<div class="dialog-widget dialog-lightbox-widget boostify-lightbox boostify-templates-modal" id="boostify-new-template-modal">
				<div class="dialog-widget-content dialog-lightbox-widget-content">
					<div class="dialog-header dialog-lightbox-header">
						<div class="boostify-templates-modal__header">
							<div class="boostify-templates-modal__header__logo-area">
								<div class="boostify-templates-modal__header__logo">
									<span class="boostify-templates-modal__header__logo__icon-wrapper boostify-gradient-logo">
										<i class="eicon-elementor"></i>
									</span>
									<span class="boostify-templates-modal__header__logo__title">New Template</span>
								</div>
							</div>
							<div class="boostify-templates-modal__header__menu-area"></div>
							<div class="boostify-templates-modal__header__items-area">
								<div class="boostify-templates-modal__header__close boostify-templates-modal__header__close--normal boostify-templates-modal__header__item">
									<i class="eicon-close" aria-hidden="true" title="Close"></i>
									<span class="boostify-screen-only">
										<?php echo esc_html__( 'Close', 'boostify' ); ?>
									</span>
								</div>

								<div id="boostify-template-library-header-tools"></div>
							</div>
						</div>
					</div>
					<div class="dialog-message dialog-lightbox-message">
						<div class="dialog-content dialog-lightbox-content">
							<div id="boostify-new-template-dialog-content">

								<form id="boostify-new-template__form" action="">
									<input type="hidden" name="post_type" value="boostify_library">
									<input type="hidden" name="action" value="boostify_new_post">
									<input type="hidden" name="_wpnonce" value="d819496b97">
									<span class="boostify-new-template__form__title">
										<?php echo esc_html__( 'Choose Template Type', 'boostify' ); ?>
									</span>
									<div id="boostify-new-template__form__template-type__wrapper" class="boostify-form-field">
										<label for="boostify-new-template__form__template-type" class="boostify-form-field__label">
											<?php echo esc_html__( 'Select the type of template you want to work on', 'boostify' ); ?>
										</label>
										<div class="boostify-form-field__select__wrapper">
											<select id="boostify-new-template__form__template-type" class="boostify-form-field__select" name="template_type" required="">
												<option value="">Select...</option>
												<option value="page">Page</option><option value="section">Section</option><option value="popup">Popup</option><option value="header">Header</option><option value="footer">Footer</option><option value="single">Single</option><option value="archive">Archive</option>				</select>
										</div>
									</div>
									<div id="boostify-new-template__form__post-type__wrapper" class="boostify-form-field" style="display: none;">
										<label for="boostify-new-template__form__post-type" class="boostify-form-field__label">
											Select Post Type</label>
										<div class="boostify-form-field__select__wrapper">
											<select id="boostify-new-template__form__post-type" class="boostify-form-field__select" name="_boostify_template_sub_type">
												<option value="">
													Select...
												</option>
												<option value="post">Post</option><option value="page">Page</option><option value="btf_builder">Elementor Builder</option><option value="lp_course">Course</option><option value="lp_lesson">Lesson</option><option value="lp_quiz">Quiz</option><option value="not_found404">404 Page</option>				</select>
										</div>
									</div>
									<div id="boostify-new-template__form__post-title__wrapper" class="boostify-form-field">
										<label for="boostify-new-template__form__post-title" class="boostify-form-field__label">
											<?php echo esc_html__( 'Name your template', 'boostify' ); ?>
										</label>
										<div class="boostify-form-field__text__wrapper">
											<input type="text" placeholder="Enter template name (optional)" id="boostify-new-template__form__post-title" class="boostify-form-field__text" name="post_data[post_title]">
										</div>
									</div>
									<button id="boostify-new-template__form__submit" class="boostify-button boostify-button-success">
										<?php echo esc_html__( 'Create Template', 'boostify' ); ?>
									</button>
								</form>
							</div>
						</div>
						<div class="dialog-loading dialog-lightbox-loading"></div>
					</div>
				</div>
			</div>
			<?php
		}

	}
}

Admin::instance();


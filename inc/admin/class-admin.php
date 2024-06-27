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
		add_action( 'wp_ajax_boostify_create_post', array( $this, 'create_bhf_post' ) );
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

		wp_enqueue_style(
			'fontawesome',
			BOOSTIFY_HEADER_FOOTER_URL . '/assets/css/awesome.css',
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
			'edit'  => admin_url( 'edit.php?post_type=btf_builder' ),
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
		$columns['type']    = __( 'Type', 'boostify' );
		$columns['display'] = __( 'Display On', 'boostify' );
		$columns['date']    = $date_column;

		return $columns;
	}

	// SHOW THE FEATURED IMAGE
	public function columns_content( $column_name, $post_id ) {
		$type       = get_post_meta( $post_id, 'bhf_type', true );
		$display_on = get_post_meta( $post_id, 'bhf_display', true );
		$post_id    = get_post_meta( $post_id, 'bhf_post', true );
		$post_type  = get_post_meta( $post_id, 'bhf_post_type', true );
		$display    = '';
		if ( 'all' == $display_on ) {
			$display = __( 'All', 'boostify' );
		} elseif ( 'blog' == $display_on ) {
			$display = __( 'Blog Page', 'boostify' );
		} elseif ( 'archive' == $display_on ) {
			$display = __( 'Archive', 'boostify' );
		} elseif ( 'search' == $display_on ) {
			$display = __( 'Search', 'boostify' );
		} elseif ( 'not_found' == $display_on ) {
			$display = __( '404 Page', 'boostify' );
		} else {
			if ( 'all' == $post_id ) {
				$display = __( 'All', 'boostify' ) . $post_type;
			} else {
				$post_array = explode( ',', $post_id );
				$list_title = array();
				foreach ( $post_array as $id ) {
					$list_title[] = get_the_title( $id );
				}

				$display = implode( ',', $list_title );
			}
		}
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

			case 'display':
				ob_start();
				?>
				<span class="bhf-type"><?php echo esc_html( $display ); ?></span>
				<?php
				ob_get_contents();
				break;
		}
	}

	public function lightbox() {
		$object     = get_current_screen();
		$post_type  = $object->post_type;
		$action     = $object->action;
		$type       = boostify_type_builder();
		$post_types = boostify_pt_support();
		if ( 'btf_builder' === $post_type ) {
			?>
			<div class="dialog-widget dialog-lightbox-widget boostify-lightbox boostify-templates-modal" id="boostify-new-template-modal">
				<div class="boostify-dialog-widget-content dialog-lightbox-widget-content">
					<div class="dialog-header dialog-lightbox-header">
						<div class="boostify-templates-modal__header">
							<div class="boostify-templates-modal__header__logo-area">
								<div class="boostify-templates-modal__header__logo">
									<span class="boostify-header-logo-icon-wrapper boostify-gradient-logo">
										<img src="<?php echo esc_url( BOOSTIFY_HEADER_FOOTER_URL . 'assets/images/boostify.png' ); ?>" alt="<?php echo esc_attr( 'Boostify Logo' ); ?>">
									</span>
									<span class="boostify-header-logo-title">
										<?php echo esc_html__( 'New Template', 'boostify' ); ?>
									</span>
								</div>
							</div>

							<div class="boostify-templates-modal__header__items-area">
								<div class="boostify-templates-modal__header__close boostify-templates-modal__header__close--normal boostify-templates-modal__header__item">
									<i class="eicon-close" aria-hidden="true" title="Back"></i>
									<span class="boostify-screen-only">
										<?php echo esc_html__( 'Back', 'boostify' ); ?>
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
									<input type="hidden" name="post_type" value="btf_builder">

									<div id="elementor-new-template__form__title">

										<?php echo esc_html__( 'Choose Template Type', 'boostify' ); ?>
									</div>
									<div class="boostify-template-first-row">
										<div id="boostify-post-title-wrapper" class="boostify-form-field">
											<label for="boostify-new-template__form__post-title" class="boostify-form-field__label"><?php echo esc_html__( 'Name your template', 'boostify' ); ?></label>
											<div class="boostify-form-field__text__wrapper">
												<input type="text" placeholder="Enter template name (optional)" id="boostify-new-template__form__post-title" class="boostify-form-field__text" name="post_title">
											</div>
										</div>
										<div id="boostify-new-template__form__template-type__wrapper" class="boostify-form-field">
											<label for="boostify-template-type" class="boostify-form-field__label">
												<?php echo esc_html__( 'Select the type of template you want to work on', 'boostify' ); ?>
											</label>
											<div class="boostify-form-field__select__wrapper">
												<select id="boostify-template-type" class="boostify-form-field__select" name="template_type" required="">
													<?php foreach ( $type as $val => $name ) : ?>
														<option value="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $name ); ?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>
									</div>
									<div class="boostify-template--row boostify-template-row-condition">
										<div class="condition-group display--on">
											<div class="parent-item boostify-form-field ">
												<label class="boostify-form-field__label" >
													<?php echo esc_html__( 'Display On', 'boostify' ); ?>
												</label>
												<div class="boostify-form-field__select__wrapper">
													<select name="bhf_display" class="display-on boostify-form-field__select">
														<?php foreach ( $post_types as $post_type => $name ) : ?>
															<option value="<?php echo esc_attr( $post_type ); ?>">
																<?php echo esc_html( $name ); ?>
															</option>
														<?php endforeach ?>

													</select>
												</div>
											</div>

											<div class="child-item">
												<div class="input-item-wrapper">
												</div>
											</div>
										</div>
										<div class="condition-group not-display">
											<div class="parent-item boostify-form-field">
												<label  class="boostify-form-field__label">
													<?php echo esc_html__( 'Do Not Display On', 'boostify' ); ?>
												</label>
												<?php unset( $post_types['all'] ); ?>
												<div class="boostify-form-field__select__wrapper">
													<select name="bhf_no_display" class="no-display-on boostify-form-field__select">
														<option value="0">Select</option>
														<?php foreach ( $post_types as $post_type => $name ) : ?>
															<option value="<?php echo esc_attr( $post_type ); ?>">
																<?php echo esc_html( $name ); ?>
															</option>
														<?php endforeach ?>
													</select>
												</div>
											</div>

											<div class="child-item">
												<div class="input-item-wrapper">
												</div>
											</div>
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

	public function create_bhf_post() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			echo esc_html__( 'You don\'t give permission', 'boostify' );
			return;
		}
		check_ajax_referer( 'ht_hf_nonce' );
		$post_type        = $_GET['post_type'];
		$post_title       = $_GET['post_title'];
		$template_type    = $_GET['template_type'];
		$display          = $_GET['bhf_display'];
		$no_display       = $_GET['bhf_no_display'];
		$bhf_post_type    = '';
		$bhf_post         = '';
		$bhf_ex_post_type = '';
		$bhf_ex_post      = '';
		$url              = admin_url( '/' );
		if ( array_key_exists( 'bhf_post_type', $_GET ) ) {
			$bhf_post_type = $_GET['bhf_post_type'];
		}
		if ( array_key_exists( 'bhf_post', $_GET ) ) {
			$bhf_post = $_GET['bhf_post'];
		}
		if ( array_key_exists( 'bhf_ex_post_type', $_GET ) ) {
			$bhf_ex_post_type = $_GET['bhf_ex_post_type'];
		}
		if ( array_key_exists( 'bhf_ex_post', $_GET ) ) {
			$bhf_ex_post = $_GET['bhf_ex_post'];
		}
		$args      = array(
			'post_title'  => $post_title,
			'post_type'   => $post_type,
			'post_status' => 'publish',
		);
		$permalink = get_option( 'permalink_structure' );

		$post_id = wp_insert_post( $args );

		add_post_meta( $post_id, 'bhf_type', $template_type );
		add_post_meta( $post_id, 'bhf_display', $display );
		add_post_meta( $post_id, 'bhf_no_display', $no_display );
		add_post_meta( $post_id, 'bhf_post_type', $bhf_post_type );
		add_post_meta( $post_id, 'bhf_post', $bhf_post );
		add_post_meta( $post_id, 'bhf_ex_post_type', $bhf_ex_post_type );
		add_post_meta( $post_id, 'bhf_ex_post', $bhf_ex_post );
		$url .= 'post.php?post=' . $post_id . '&action=elementor';
		wp_send_json( $url );

		die();
	}
}

Admin::instance();


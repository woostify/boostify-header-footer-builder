<?php
/**
 * Main Boostify Header Footer Metabox Class
 *
 * @class Boostify_Header_Footer_Metabox
 *
 * @package Boostify_Header_Footer_Template
 */

namespace Boostify_Header_Footer;

defined( 'ABSPATH' ) || exit;

/**
 * Main Boostify Header Footer Metabox Class
 *
 * @class Boostify_Header_Footer_Metabox
 */
class Metabox {

	/**
	 * Boostify Header Footer Metabox Constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Register Hooks.
	 */
	public function hooks() {
		add_action( 'add_meta_boxes', array( $this, 'pagesetting_meta_box' ) );
		add_action( 'save_post', array( $this, 'pagesetting_save' ) );
		add_action( 'wp_ajax_boostify_hf_load_autocomplate', array( $this, 'boostify_hf_input' ) );
		add_action( 'wp_ajax_boostify_hf_post_admin', array( $this, 'boostify_hf_post_admin' ) );
		add_action( 'wp_ajax_bhf_more_rule', array( $this, 'parent_rule' ) );
		add_action( 'wp_ajax_boostify_hf_ex_auto', array( $this, 'boostify_hf_post_exclude' ) );
		add_action( 'wp_ajax_boostify_hf_type', array( $this, 'display_setting' ) );
	}

	/**
	 * Meta Box In btf_builder post type.
	 */
	public function pagesetting_meta_box() {
		add_meta_box( 'ht_hf_setting', 'Template Settings', array( $this, 'ht_hfsetting_output' ), 'btf_builder', 'side', 'high' );
	}

	/**
	 * Screen meta box in btf_builder post type.
	 */
	public function ht_hfsetting_output( $post ) {
		$types         = boostify_type_builder();
		$type          = get_post_meta( $post->ID, 'bhf_type', true );
		$display       = get_post_meta( $post->ID, 'bhf_display', true );
		$posts         = get_post_meta( $post->ID, 'bhf_post', true );
		$post_type     = get_post_meta( $post->ID, 'bhf_post_type', true );
		$select_footer = '';
		$select_header = '';

		wp_nonce_field( 'boostify_hf_action', 'boostify_hf' );
		?>

		<div class="form-meta-footer">

			<!-- Choose Header or Footer -->
			<div class="input-wrapper">
				<label for="container"><?php echo esc_html__( 'Type of Template', 'boostify' ); ?></label>
				<select name="bhf_type" id="container">
					<?php foreach ( $types as $key => $val ) : ?>
						<?php $selected = ( $key === $type ) ? 'selected' : ''; ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $val ); ?></option>
					<?php endforeach ?>
				</select>
			</div>

			<?php
			if ( 'sub_menu' !== $type ) {
				$this->hf_display( $post );
			}

			?>

		</div>
		<?php
	}

	/**
	 * Save meta box setting in btf_buider postType.
	 */
	public function pagesetting_save( $post_id ) {
		$nonce_name   = ( array_key_exists( 'boostify_hf', $_POST ) ) ? sanitize_text_field( $_POST['boostify_hf'] ) : '';
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

		// Type of Template Builder.
		$type = sanitize_text_field( $_POST['bhf_type'] );
		if ( empty( $type ) ) {
			$type = 'header';
		}

		update_post_meta(
			$post_id,
			'bhf_type',
			$type
		);

		if ( 'sub_menu' !== $type ) {

			// Display On.
			$display = sanitize_text_field( $_POST['bhf_display'] );

			update_post_meta(
				$post_id,
				'bhf_display',
				$display
			);

			// Do Not Display On.
			$no_display = sanitize_text_field( $_POST['bhf_no_display'] );

			update_post_meta(
				$post_id,
				'bhf_no_display',
				$no_display
			);

			// Post.
			if ( array_key_exists( 'bhf_post', $_POST ) ) {
				$post = sanitize_text_field( $_POST['bhf_post'] );

				update_post_meta(
					$post_id,
					'bhf_post',
					$post
				);
			} else {
				update_post_meta(
					$post_id,
					'bhf_post',
					''
				);
			}

			// Ex Post.
			if ( array_key_exists( 'bhf_ex_post', $_POST ) ) {
				$ex_post = sanitize_text_field( $_POST['bhf_ex_post'] );

				update_post_meta(
					$post_id,
					'bhf_ex_post',
					$ex_post
				);
			} else {
				update_post_meta(
					$post_id,
					'bhf_ex_post',
					''
				);
			}

			// Ex Post Type.
			if ( array_key_exists( 'bhf_ex_post_type', $_POST ) ) {
				$ex_post_type = sanitize_text_field( $_POST['bhf_ex_post_type'] );

				update_post_meta(
					$post_id,
					'bhf_ex_post_type',
					$ex_post_type
				);
			} else {
				update_post_meta(
					$post_id,
					'bhf_ex_post_type',
					''
				);
			}

			// Post Type.
			if ( array_key_exists( 'bhf_post_type', $_POST ) ) {
				$post_type = sanitize_text_field( $_POST['bhf_post_type'] );

				update_post_meta(
					$post_id,
					'bhf_post_type',
					$post_type
				);
			} else {
				update_post_meta(
					$post_id,
					'bhf_post_type',
					''
				);
			}
		}

	}

	public function hf_display( $post ) {

		$options      = boostify_pt_support();
		$display      = get_post_meta( $post->ID, 'bhf_display', true );
		$no_display   = get_post_meta( $post->ID, 'bhf_no_display', true );
		$post_id      = get_post_meta( $post->ID, 'bhf_post', true );
		$post_type    = get_post_meta( $post->ID, 'bhf_post_type', true );
		$ex_post_id   = get_post_meta( $post->ID, 'bhf_ex_post', true );
		$ex_post_type = get_post_meta( $post->ID, 'bhf_ex_post_type', true );
		$list_post    = $post_id;
		$list_ex_post = $ex_post_id;
		if ( 'all' !== $post_id ) {
			$list_post = explode( ',', $post_id );
		}

		if ( 'all' !== $ex_post_id ) {
			$list_ex_post = explode( ',', $ex_post_id );
		}

		?>
			<div class="input-wrapper">
				<div class="condition-group display--on">
					<div class="parent-item">
						<label><?php echo esc_html__( 'Display On', 'boostify' ); ?></label>
						<select name="bhf_display" class="display-on">
							<?php
							foreach ( $options as $key => $option ) :
								$selected = ( $key == $display ) ? 'selected' : ''; // phpcs:ignore
								?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $option ); ?></option>
							<?php endforeach ?>
						</select>
					</div>

					<div class="child-item">
						<div class="input-item-wrapper">
							<?php
							if ( ! empty( $post_id ) && ! empty( $post_type ) ) :

								?>
							<div class="boostify-section-select-post <?php echo ( is_string( $list_post ) ? 'select-all' : 'render--post has-option' ); ?>">

								<span class="boostify-select-all-post<?php echo ( is_string( $list_post ) ? '' : ' hidden' ); ?>">
									<span class="boostify-select-all"><?php echo esc_html__( 'All', 'boostify' ); ?></span>
									<span class="boostify-arrow ion-chevron-down"></span>
								</span>

								<div class="boostify-section-render--post <?php echo ( is_string( $list_post ) ? 'hidden' : '' ); ?>">
									<div class="boostify-auto-complete-field">
										<?php
										if ( is_array( $list_post ) ) :

											foreach ( $list_post as $id ) :
												$id = (int) $id;
												?>

												<span class="boostify-auto-complete-key">
													<span class="boostify-title"><?php echo esc_html( get_the_title( $id ) ); ?></span>
													<span class="btn-boostify-auto-complete-delete ion-close" data-item="<?php echo esc_attr( $id ); ?>"></span>
												</span>
												<?php
											endforeach;
										endif;
										?>
										<input type="text" class="boostify--hf-post-name" aria-autocomplete="list" size="1">
									</div>
								</div>

							</div>
							<input type="hidden" name="bhf_post" value="<?php echo esc_html( $post_id ); ?>">
							<input type="hidden" name="bhf_post_type" value="<?php echo esc_attr( $post_type ); ?>" class="bhf-post-type">
							<div class="boostify-data"></div>
								<?php
							endif;
							?>
						</div>
					</div>
				</div>

				<div class="condition-group not-display">
					<div class="parent-item">
						<label><?php echo esc_html__( 'Do Not Display On', 'boostify' ); ?></label>
						<select name="bhf_no_display" class="no-display-on">
							<?php
							unset( $options['all'] );
							?>
							<option value="0"><?php echo esc_html__( 'Select', 'boostify' ); ?></option>
							<?php
							foreach ( $options as $key => $option ) :
								$selected = ( $key == $no_display ) ? 'selected' : ''; // phpcs:ignore
								?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $option ); ?></option>
							<?php endforeach ?>
						</select>
					</div>

					<div class="child-item">
						<div class="input-item-wrapper">
							<?php
							if ( ! empty( $ex_post_id ) && ! empty( $ex_post_type ) ) :

								?>
							<div class="boostify-section-select-post <?php echo ( is_string( $list_ex_post ) ? 'select-all' : 'render--post has-option' ); ?>">

								<span class="boostify-select-all-post<?php echo ( is_string( $list_ex_post ) ? '' : ' hidden' ); ?>">
									<span class="boostify-select-all"><?php echo esc_html__( 'All', 'boostify' ); ?></span>
									<span class="boostify-arrow ion-chevron-down"></span>
								</span>

								<div class="boostify-section-render--post <?php echo ( is_string( $list_ex_post ) ? 'hidden' : '' ); ?>">
									<div class="boostify-auto-complete-field">
										<?php
										if ( is_array( $list_ex_post ) ) :

											foreach ( $list_ex_post as $id ) :
												$id = (int) $id;
												?>

												<span class="boostify-auto-complete-key">
													<span class="boostify-title"><?php echo esc_html( get_the_title( $id ) ); ?></span>
													<span class="btn-boostify-auto-complete-delete ion-close" data-item="<?php echo esc_attr( $id ); ?>"></span>
												</span>
												<?php
											endforeach;
										endif;
										?>
										<input type="text" class="boostify--hf-post-name" aria-autocomplete="list" size="1">
									</div>
								</div>

							</div>
							<input type="hidden" name="bhf_ex_post_type" value="<?php echo esc_attr( $ex_post_type ); ?>" class="bhf-post-type">
							<input type="hidden" name="bhf_ex_post" value="<?php echo esc_html( $ex_post_id ); ?>">
							<div class="boostify-data"></div>
								<?php
							endif;
							?>
						</div>
					</div>
				</div>
			</div>
		<?php
	}

	public function boostify_hf_post_admin() {
		check_ajax_referer( 'ht_hf_nonce', '_ajax_nonce' );
		$post_type = sanitize_text_field( $_GET['post_type'] );
		$keyword   = sanitize_text_field( $_GET['key'] );

		$the_query = new \WP_Query(
			array(
				's'              => $keyword,
				'posts_per_page' => -1,
				'post_type'      => $post_type,
			)
		);

		if ( $the_query->have_posts() ) {
			?>
			<div class="boostify-hf-list-post">
				<ul class="hf-list-post">
				<?php
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$results[ get_the_ID() ] = get_the_title();
					?>
						<li class="post-item" data-item="<?php echo esc_attr( get_the_ID() ); ?>">
							<?php the_title(); ?>
						</li>

					<?php
				}
				?>
				</ul>
			</div>
			<?php

			/* Restore original Post Data */

			wp_reset_postdata();

		} else {
			?>
			<div class="boostify-hf-list-post">
				<h6><?php echo esc_html__( 'Nothing Found', 'boostify' ); ?></h6>
			</div>
			<?php
		}

		die();

	}

	public function get_posts( $post_type ) {
		$args  = array(
			'post_type'      => $post_type,
			'orderby'        => 'name',
			'order'          => 'ASC',
			'posts_per_page' => -1,
		);
		$posts = new \WP_Query( $args );

		return $posts;
	}


	// For Ajax For Select single post display
	public function boostify_hf_input() {
		check_ajax_referer( 'ht_hf_nonce', 'token' );

		$post_type = sanitize_text_field( $_POST['post_type'] );

		if ( 'all' !== $post_type && 'archive' !== $post_type && 'search' !== $post_type && 'blog' !== $post_type && 'not_found' !== $post_type ) :
			?>
			<div class="input-item-wrapper">
				<div class="boostify-section-select-post">
					<span class="boostify-select-all-post">
						<span class="boostify-select-all"><?php echo esc_html__( 'All', 'boostify' ); ?></span>
						<span class="boostify-arrow ion-chevron-down"></span>
					</span>
					<div class="boostify-section-render--post hidden">
						<div class="boostify-auto-complete-field">
							<input type="text" class="boostify--hf-post-name" aria-autocomplete="list" size="1">
						</div>
					</div>
				</div>
				<input type="hidden" name="bhf_post_type" value="<?php echo esc_attr( $post_type ); ?>" class="bhf-post-type">
				<input type="hidden" name="bhf_post" value="all">
				<div class="boostify-data"></div>
			</div>
			<?php
		endif;
		die();
	}

	// For Ajax For Select single post not display
	public function boostify_hf_post_exclude() {
		check_ajax_referer( 'ht_hf_nonce' );
		$post_type = sanitize_text_field( $_POST['post_type'] );

		if ( $post_type && 'all' !== $post_type && 'archive' !== $post_type && 'search' !== $post_type && 'blog' !== $post_type && 'not_found' !== $post_type ) :
			?>
			<div class="input-item-wrapper">
				<div class="boostify-section-select-post">
					<span class="boostify-select-all-post">
						<span class="boostify-select-all"><?php echo esc_html__( 'All', 'boostify' ); ?></span>
						<span class="boostify-arrow ion-chevron-down"></span>
					</span>
					<div class="boostify-section-render--post hidden">
						<div class="boostify-auto-complete-field">
							<input type="text" class="boostify--hf-post-name" aria-autocomplete="list" size="1">
						</div>
					</div>
				</div>
				<input type="hidden" name="bhf_ex_post_type" value="<?php echo esc_attr( $post_type ); ?>" class="bhf-post-type">
				<input type="hidden" name="bhf_ex_post" value="all">

				<div class="boostify-data"></div>
			</div>
			<?php
		endif;
		die();
	}


	public function parent_rule() {
		check_ajax_referer( 'ht_hf_nonce' );

		$options = boostify_pt_support();
		$length  = $_GET['length'];
		?>
		<div class="condition-group">
			<div class="parent-item">
				<label><?php echo esc_html__( 'Display On', 'boostify' ); ?></label>
				<select name="bhf_condition[<?php echo esc_html( $length ); ?>]" class="display-on">
					<?php
					foreach ( $options as $key => $option ) :
						?>
						<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $option ); ?></option>
					<?php endforeach ?>
				</select>
			</div>

			<div class="child-item">
			</div>
		</div>
		<?php

		die();
	}

	// For Show Setting type Header, Footer
	public function display_setting() {
		check_ajax_referer( 'ht_hf_nonce' );

		$options = boostify_pt_support();
		$type    = $_GET['type'];
		if ( 'sub_menu' !== $type ) :

			?>
			<div class="input-wrapper">
				<div class="condition-group display--on">
					<div class="parent-item">
						<label><?php echo esc_html__( 'Display On', 'boostify' ); ?></label>
						<select name="bhf_display" class="display-on">
							<?php
							foreach ( $options as $key => $option ) :

								?>
								<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $option ); ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="child-item">
						<div class="input-item-wrapper">
						</div>
					</div>
				</div>

				<div class="condition-group not-display">
					<div class="parent-item">
						<label><?php echo esc_html__( 'Do Not Display On', 'boostify' ); ?></label>
						<select name="bhf_no_display" class="no-display-on">
							<?php
							unset( $options['all'] );
							?>
							<option value="0"><?php echo esc_html__( 'Select', 'boostify' ); ?></option>
							<?php
							foreach ( $options as $key => $option ) :

								?>
								<option value="<?php echo esc_attr( $key ); ?>" ><?php echo esc_html( $option ); ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="child-item">
						<div class="input-item-wrapper">
						</div>
					</div>

				</div>

			</div>
			<?php
		endif;
		die();
	}
}


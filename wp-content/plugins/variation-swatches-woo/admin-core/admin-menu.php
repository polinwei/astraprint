<?php
/**
 * Admin menu.
 *
 * @package variation-swatches-woo
 * @since 1.0.0
 */

namespace CFVSW\Admin_Core;

use CFVSW\Inc\Traits\Get_Instance;
use CFVSW\Inc\Helper;


/**
 * Admin menu
 *
 * @since 1.0.0
 */
class Admin_Menu {

	use Get_Instance;

	/**
	 * Tailwind assets base url
	 *
	 * @var string
	 * @since  1.0.0
	 */
	private $tailwind_assets = CFVSW_URL . 'admin-core/assets/build/';

	/**
	 * Instance of Helper class
	 *
	 * @var Helper
	 * @since  1.0.0
	 */
	private $helper;

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 */
	public function __construct() {
		$this->helper = new Helper();
		add_action( 'admin_menu', [ $this, 'settings_page' ], 99 );
		add_action( 'admin_enqueue_scripts', [ $this, 'settings_page_scripts' ] );
		add_action( 'wp_ajax_cfvsw_update_settings', [ $this, 'cfvsw_update_settings' ] );
		add_action( 'admin_head', [ $this, 'hide_notices' ] );
	}

	/**
	 * Adds admin menu for settings page
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function settings_page() {
		add_submenu_page(
			'woocommerce',
			__( 'Settings', 'variation-swatches-woo' ),
			__( 'Variation Swatches', 'variation-swatches-woo' ),
			'manage_woocommerce',
			'cfvsw_settings',
			[ $this, 'render' ]
		);
	}

	/**
	 * Renders main div to implement tailwind UI
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function render() {
		?>
		<div class="cfvsw-settings" id="cfvsw-settings"></div>
		<?php
	}

	/**
	 * Enqueue settings page script and style
	 *
	 * @param string $hook current page hook.
	 * @return void
	 * @since  1.0.0
	 */
	public function settings_page_scripts( $hook ) {

		if ( 'woocommerce_page_cfvsw_settings' !== $hook ) {
			return;
		}
		$script_asset_path = CFVSW_DIR . 'admin-core/assets/build/settings.asset.php';
		$script_info       = file_exists( $script_asset_path )
			? include $script_asset_path
			: array(
				'dependencies' => [],
				'version'      => CFVSW_VER,
			);

		$script_dep = array_merge( $script_info['dependencies'], array( 'updates' ) );

		wp_register_script( 'cfvsw_settings', $this->tailwind_assets . 'settings.js', $script_dep, CFVSW_VER, true );
		wp_enqueue_script( 'cfvsw_settings' );
		wp_localize_script(
			'cfvsw_settings',
			'cfvsw_settings',
			[
				'ajax_url'     => admin_url( 'admin-ajax.php' ),
				'update_nonce' => wp_create_nonce( 'cfvsw_update_settings' ),
				CFVSW_GLOBAL   => $this->helper->get_option( CFVSW_GLOBAL ),
				CFVSW_SHOP     => $this->helper->get_option( CFVSW_SHOP ),
				CFVSW_STYLE    => $this->helper->get_option( CFVSW_STYLE ),
			]
		);

		wp_register_style( 'cfvsw_settings', $this->tailwind_assets . 'settings.css', [], CFVSW_VER );
		wp_enqueue_style( 'cfvsw_settings' );
		wp_style_add_data( 'cfvsw_settings', 'rtl', 'replace' );
	}

	/**
	 * Ajax handler for submit action on settings page.
	 * Updates settings data in database.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function cfvsw_update_settings() {
		check_ajax_referer( 'cfvsw_update_settings', 'security' );
		$keys = [];
		if ( ! empty( $_POST[ CFVSW_GLOBAL ] ) ) {
			$keys[] = CFVSW_GLOBAL;
		}

		if ( ! empty( $_POST[ CFVSW_SHOP ] ) ) {
			$keys[] = CFVSW_SHOP;
		}

		if ( ! empty( $_POST[ CFVSW_STYLE ] ) ) {
			$keys[] = CFVSW_STYLE;
		}

		if ( empty( $keys ) ) {
			wp_send_json_error( [ 'message' => __( 'No valid setting keys found.', 'variation-swatches-woo' ) ] );
		}

		$succeded = 0;
		foreach ( $keys as $key ) {
			if ( $this->update_settings( $key, $_POST[ $key ] ) ) {
				$succeded++;
			}
		}

		if ( count( $keys ) === $succeded ) {
			wp_send_json_success( [ 'message' => __( 'Settings saved successfully.', 'variation-swatches-woo' ) ] );
		}

		wp_send_json_error( [ 'message' => __( 'Failed to save settings.', 'variation-swatches-woo' ) ] );
	}

	/**
	 * Update dettings data in database
	 *
	 * @param string $key options key.
	 * @param string $data user input to be saved in database.
	 * @return boolean
	 * @since  1.0.0
	 */
	public function update_settings( $key, $data ) {
		$data = ! empty( $data) ? json_decode( stripslashes( $data ), true ) : array(); // phpcs:ignore
		$data         = $this->sanitize_data( $data );
		$default_data = $this->helper->get_option( $key );
		if ( $data === $default_data ) {
			return true;
		}
		$data = wp_parse_args( $data, $default_data );
		return update_option( $key, $data );
	}

	/**
	 * Sanitize data as per data type
	 *
	 * @param array $data raw input received from user.
	 * @return array
	 * @since  1.0.0
	 */
	public function sanitize_data( $data ) {
		$temp     = [];
		$booleans = [
			'override_global',
			'enable_swatches',
			'enable_swatches_shop',
			'auto_convert',
			'tooltip',
			'label',
		];

		$numbers = [
			'min_width',
			'min_height',
			'border_radius',
		];

		foreach ( $data as $key => $value ) {
			if ( in_array( $key, $booleans, true ) ) {
				$temp[ $key ] = rest_sanitize_boolean( $value );
			} elseif ( in_array( $key, $numbers, true ) ) {
				$temp[ $key ] = (int) sanitize_text_field( $value );
			} else {
				$temp[ $key ] = sanitize_text_field( $value );
			}
		}

		return $temp;
	}

	/**
	 * Hides admin notices on Variation Swatches settings page
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function hide_notices() {
		$screen = get_current_screen();

		if ( 'woocommerce_page_cfvsw_settings' === $screen->id ) {
			remove_all_actions( 'admin_notices' );
		}

	}
}

<?php
/**
 * Plugin Name: Layout Grid for Elementor
 * Description: An awesome set of tools/options/settings that extend Elementor default/existing widgets and elements. It keeps the editor tidy, saves valuable resources and improves the workflow.
 * Version:     1.0.0
 * Author:      Code Dragon
 * Author URI:  https://www.youtube.com/@codedragon5
 * Text Domain: ele-layout-grid
 * Domain Path: 
 * License: GPLv3
 * Elementor tested up to: 3.9.1
 * Elementor Pro tested up to: 3.9.1
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 */

//use Elementor\Core\Settings\Manager as SettingsManager;

defined( 'ABSPATH' ) || die(); // Exit if accessed directly.

/**
 * Main Ele Layout Grid Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Ele_Layout_Grid { 

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.6';

	/**
	 * Elementor Version for Containers
	 *
	 * @since 1.9.1
	 *
	 * @var string Elementor version required for particular extensions to work
	 */
	const ELEMENTOR_VERSION_CONTAINER = '3.6';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	*/
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Ele_Layout_Grid The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Ele_Layout_Grid An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	
	public function __construct() {	
		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	// /**
	//  * Load Textdomain
	//  *
	//  * Load plugin localization files.
	//  *
	//  * Fired by `init` action hook.
	//  *
	//  * @since 1.0.0
	//  *
	//  * @access public
	//  */
	// public function i18n() {
	// 	load_plugin_textdomain( 'ele-layout-grid', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
	// }

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by 'plugins_loaded' action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version			
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		self::cd_fire_it_up();

		
	 }

	/*
		* Check main plugin
		*
		* @since 1.9.0
		*
		* @access public
	*/
    public function admin_notice_missing_main_plugin() {

        if( isset( $_GET[ 'activate' ] ) ) unset( $_GET[ 'activate' ] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'ele-layout-grid' ),
            '<strong>' . esc_html__( 'Elementor Layout Grid', 'ele-layout-grid' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'ele-layout-grid' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ele-layout-grid' ),
			'<strong>' . esc_html__( 'Elementor Layout Grid', 'ele-layout-grid' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'ele-layout-grid' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ele-layout-grid' ),
			'<strong>' . esc_html__( 'Elementor Layout Grid', 'ele-layout-grid' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'ele-layout-grid' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}
	
	

	
   public static function cd_fire_it_up() {

	   include_once plugin_dir_path( __FILE__ ) . 'controls/cd-layout-grid.php'; 

	}

}

Ele_Layout_Grid::instance();

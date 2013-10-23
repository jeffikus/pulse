<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Heartbeat API Pulse Class
 *
 * All functionality pertaining to the Pulse Heartbeat API.
 *
 * @package WordPress
 * @subpackage Heartbeat API
 * @category Frontend
 * @author Jeffikus
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * - __construct()
 * - init()
 * - enqueue_scripts()
 * - enqueue_styles()
 * - heartbeat_settings()
 * - respond_to_browser_unauthenticated
 * - respond_to_browser_authenticated
 */
class Heartbeat_API_Pulse {
	public $token;
	public $plugin_url;
	public $version;

	/**
	 * Constructor.
	 * @since  1.0.0
	 * @return  void
	 */
	public function __construct ( $file ) {

		// Class variables
        $this->token = 'heartbeat-api-pulse';
		$this->plugin_url = trailingslashit( plugins_url( '', $file ) );

        // Actions & filters
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_styles' ) );
    	add_action( 'wp_footer', array( &$this, 'enqueue_scripts' ) );
    	add_filter( 'heartbeat_settings', array( &$this, 'heartbeat_settings' ) );

	} // End __construct()

	/**
	 * Initialise the code.
	 * @since  1.0.0
	 * @return void
	 */
	public function init () {

        // Heartbeat filters
		add_filter( 'heartbeat_received', array( &$this, 'respond_to_browser_authenticated' ), 5, 2 );
		add_filter( 'heartbeat_nopriv_received', array( &$this, 'respond_to_browser_unauthenticated' ), 5, 2 );

	} // End init()

	/**
     * Enqueue frontend JavaScripts.
     * @since  1.0.0
     * @return void
     */
    public function enqueue_scripts () {

        // Load the pulse javascript
        wp_enqueue_script( $this->token . '-pulse' , $this->plugin_url . 'assets/js/pulse.js', array( 'jquery', 'heartbeat' ), '1.0.0', true );

    } // End enqueue_scripts()

    /**
     * Enqueue frontend CSS files.
     * @since  1.0.0
     * @return void
     */
    public function enqueue_styles () {

        // Load the pulse frontend CSS
        wp_register_style( $this->token . '-frontend', $this->plugin_url . 'assets/css/frontend.css', '', '1.0.0', 'screen' );
        wp_enqueue_style( $this->token . '-frontend' );

    } // End enqueue_styles()

    /**
     * Sets heartbeat tick interval.
     * @since  1.0.0
     * @return void
     */
    public function heartbeat_settings( $settings ) {

    	$settings['interval'] = 15; //Anything between 15-60
    	// $settings['autostart'] = false;
    	return $settings;

	} // End heartbeat_settings

    /**
     * Handle send data and respond to the browser.
     * @since  1.0.0
     * @return $response data to the heartbeat tick function
     */
	public function respond_to_browser_unauthenticated( $response, $data ) {

        // Do custom code here
        $data['user'] = 'User is not logged in.';
        $data['php'] = 'Sent from PHP.';
	return $data;

	} // End respond_to_browser_unauthenticated()

    /**
     * Handle authenticated user (logged in) send data and respond to the browser.
     * @since  1.0.0
     * @return $response data to the heartbeat tick function
     */
    public function respond_to_browser_authenticated( $response, $data ) {

        // Do custom code here
        $data['user'] = 'User is logged in.';
        $data['php'] = 'Sent from PHP.';
        return $data;

    } // End respond_to_browser_authenticated()

} // End Heartbeat_API_Pulse


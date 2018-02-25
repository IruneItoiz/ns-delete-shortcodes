<?php

/*
Plugin Name: Northset Delete Shortcodes
Plugin URI:
Description: Delete all instances of a shortcode from within the content of posts/other post types.
Version: 1.0
Author: Irune Itoiz
Author URI: http://irune.io/
*/

// Exit if this file is called directly
if ( ! defined( 'WPINC' ) ) {
	die;
}

$delete_shortcodes = new NS_Delete_Shortcodes();

class NS_Delete_Shortcodes {

	public function __construct() {
		$this->include_dependencies();
		$this->setup();
		$this->deactivate();
		$this->instantiate_components();
	}

	/**
	 * Include all dependencies
	 */
	public function include_dependencies() {
		require_once 'src/class-settings.php';
		require_once 'src/class-shortcode_remover.php';
	}

	/**
	 * Plugin activation
	 */
	public function setup() {
		register_activation_hook( __FILE__, array( $this, 'plugin_activate' ) );
	}

	/**
	 * Plugin deactivation
	 */
	public function deactivate() {
		register_deactivation_hook( __FILE__, array( $this, 'plugin_deactivate' ) );
	}

	/**
	 * Instantiates all components of plugin
	 */
	public function instantiate_components() {
		$settings = new Northset\Delete_Shortcodes\Settings;
		$settings->init();
	}

	/**
	 * Run this on plugin activation
	 */
	public function plugin_activate() {
		//Do nothing
	}

	/**
	 * Clean up after deactivation
	 */
	public function plugin_deactivate() {
		//Do nothing
	}
}

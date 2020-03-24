<?php
/**
 * Plugin Name: Tour Operator Activities
 * Plugin URI:  https://www.lsdev.biz/product/tour-operator-activities/
 * Description: The Tour Operator Activities extension adds “Activities” as a post type, which can be featured as part of a tour, at a specific destination, at an accommodation, and so on.
 * Version:     1.1.1
 * Author:      LightSpeed
 * Author URI:  https://www.lsdev.biz/
 * License:     GPL3+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lsx-activities
 * Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LSX_ACTIVITIES_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_ACTIVITIES_CORE', __FILE__ );
define( 'LSX_ACTIVITIES_URL', plugin_dir_url( __FILE__ ) );
define( 'LSX_ACTIVITIES_VER', '1.1.1' );

/* ======================= Below is the Plugin Class init ========================= */

require_once LSX_ACTIVITIES_PATH . '/classes/class-lsx-activities.php';

<?php
/**
 * Plugin Name: TradePilot AI
 * Plugin URI:  https://github.com/revpip/tradepilot-ai
 * Description: Intelligent operating system for trade businesses: leads, quotes, routing, automation, reviews, analytics and franchise growth.
 * Version:     0.6.0
 * Author:      TradePilot AI
 * Text Domain: tradepilot-ai
 * Domain Path: /languages
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * TradePilot Core
 * Module: Bootstrap
 * Function: Defines core constants and starts the plugin.
 * Version: 0.6.0
 */
define('TRADEPILOT_AI_VERSION', '0.6.0');
define('TRADEPILOT_AI_FILE', __FILE__);
define('TRADEPILOT_AI_PATH', plugin_dir_path(__FILE__));
define('TRADEPILOT_AI_URL', plugin_dir_url(__FILE__));
define('TRADEPILOT_AI_BASENAME', plugin_basename(__FILE__));

require_once TRADEPILOT_AI_PATH . 'includes/core/class-tradepilot-core.php';

register_activation_hook(__FILE__, array('TradePilot_Core', 'activate'));
register_deactivation_hook(__FILE__, array('TradePilot_Core', 'deactivate'));

add_action('plugins_loaded', array('TradePilot_Core', 'init'));

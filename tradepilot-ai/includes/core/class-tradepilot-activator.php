<?php
/**
 * TradePilot Core
 * Module: Activator
 * Function: Handles safe installation and upgrade tasks.
 * Version: 0.4.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class TradePilot_Activator {

    /**
     * TradePilot Core
     * Module: Activator
     * Function: Save installed version, defaults, module registry and database tables.
     * Version: 0.4.0
     */
    public static function activate() {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        require_once TRADEPILOT_AI_PATH . 'includes/database/class-tradepilot-database.php';
        require_once TRADEPILOT_AI_PATH . 'includes/core/class-tradepilot-modules.php';

        TradePilot_Database::install();
        update_option('tradepilot_ai_version', TRADEPILOT_AI_VERSION, false);

        if (!get_option('tradepilot_ai_install_date')) {
            update_option('tradepilot_ai_install_date', current_time('mysql'), false);
        }

        if (!get_option('tradepilot_ai_settings')) {
            update_option('tradepilot_ai_settings', array(), false);
        }

        if (!get_option('tradepilot_ai_modules')) {
            update_option('tradepilot_ai_modules', TradePilot_Modules::registry(), false);
        }
    }
}

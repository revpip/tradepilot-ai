<?php
/**
 * TradePilot Core
 * Module: Activator
 * Function: Handles safe installation and upgrade tasks.
 * Version: 0.2.0
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
     * Function: Save installed version and prepare future database installation.
     * Version: 0.2.0
     */
    public static function activate() {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        update_option('tradepilot_ai_version', TRADEPILOT_AI_VERSION, false);
        update_option('tradepilot_ai_install_date', current_time('mysql'), false);
    }
}

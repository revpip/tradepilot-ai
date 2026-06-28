<?php
/**
 * TradePilot Core
 * Module: Core Loader
 * Function: Loads core services, admin screens and registered intelligence modules.
 * Version: 0.2.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class TradePilot_Core {

    /**
     * TradePilot Core
     * Module: Core Loader
     * Function: Initialise plugin systems after WordPress loads.
     * Version: 0.2.0
     */
    public static function init() {
        self::load_core_files();
        self::load_modules();

        if (is_admin()) {
            require_once TRADEPILOT_AI_PATH . 'admin/class-tradepilot-admin.php';
            TradePilot_Admin::init();
        }
    }

    /**
     * TradePilot Core
     * Module: Core Loader
     * Function: Load internal framework files.
     * Version: 0.2.0
     */
    private static function load_core_files() {
        require_once TRADEPILOT_AI_PATH . 'includes/core/class-tradepilot-activator.php';
        require_once TRADEPILOT_AI_PATH . 'includes/core/class-tradepilot-modules.php';
    }

    /**
     * TradePilot Core
     * Module: Module Loader
     * Function: Load registered module stubs.
     * Version: 0.2.0
     */
    private static function load_modules() {
        TradePilot_Modules::load();
    }

    /**
     * TradePilot Core
     * Module: Activation
     * Function: Run install tasks safely when plugin activates.
     * Version: 0.2.0
     */
    public static function activate() {
        require_once TRADEPILOT_AI_PATH . 'includes/core/class-tradepilot-activator.php';
        TradePilot_Activator::activate();
    }

    /**
     * TradePilot Core
     * Module: Deactivation
     * Function: Run safe deactivation tasks without deleting data.
     * Version: 0.2.0
     */
    public static function deactivate() {
        // Reserved for scheduled task cleanup in a future build.
    }
}

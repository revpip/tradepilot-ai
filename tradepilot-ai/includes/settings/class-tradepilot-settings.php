<?php
/**
 * TradePilot Core
 * Module: Settings Framework
 * Function: Provides default settings and safe accessors for platform configuration.
 * Version: 0.3.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class TradePilot_Settings {

    /**
     * TradePilot Core
     * Module: Settings Framework
     * Function: Return default settings structure.
     * Version: 0.3.0
     */
    public static function defaults() {
        return array(
            'business_name' => '',
            'support_email' => get_option('admin_email'),
            'service_areas' => array(),
            'ai_enabled'    => false,
            'hot_threshold' => 80,
            'warm_threshold'=> 50,
        );
    }

    /**
     * TradePilot Core
     * Module: Settings Framework
     * Function: Return merged saved settings.
     * Version: 0.3.0
     */
    public static function get_all() {
        $saved = get_option('tradepilot_ai_settings', array());

        if (!is_array($saved)) {
            $saved = array();
        }

        return wp_parse_args($saved, self::defaults());
    }

    /**
     * TradePilot Core
     * Module: Settings Framework
     * Function: Return one setting by key.
     * Version: 0.3.0
     */
    public static function get($key, $fallback = null) {
        $settings = self::get_all();
        return array_key_exists($key, $settings) ? $settings[$key] : $fallback;
    }
}

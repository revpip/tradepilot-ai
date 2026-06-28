<?php
/**
 * TradePilot Core
 * Module: Settings Framework
 * Function: Provides default settings, sanitisation and safe accessors.
 * Version: 0.4.0
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
     * Version: 0.4.0
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
     * Version: 0.4.0
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
     * Version: 0.4.0
     */
    public static function get($key, $fallback = null) {
        $settings = self::get_all();
        return array_key_exists($key, $settings) ? $settings[$key] : $fallback;
    }

    /**
     * TradePilot Core
     * Module: Settings Framework
     * Function: Sanitise and save admin settings from form submission.
     * Version: 0.4.0
     */
    public static function save_from_post($post_data) {
        $current = self::get_all();

        $business_name = isset($post_data['business_name']) ? sanitize_text_field(wp_unslash($post_data['business_name'])) : '';
        $support_email = isset($post_data['support_email']) ? sanitize_email(wp_unslash($post_data['support_email'])) : '';
        $areas_raw     = isset($post_data['service_areas']) ? sanitize_textarea_field(wp_unslash($post_data['service_areas'])) : '';
        $hot_threshold = isset($post_data['hot_threshold']) ? absint($post_data['hot_threshold']) : 80;
        $warm_threshold= isset($post_data['warm_threshold']) ? absint($post_data['warm_threshold']) : 50;

        $service_areas = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $areas_raw)));
        $service_areas = array_map('sanitize_text_field', $service_areas);

        $settings = array(
            'business_name' => $business_name,
            'support_email' => is_email($support_email) ? $support_email : $current['support_email'],
            'service_areas' => array_values($service_areas),
            'ai_enabled'    => !empty($post_data['ai_enabled']),
            'hot_threshold' => min(100, max(0, $hot_threshold)),
            'warm_threshold'=> min(100, max(0, $warm_threshold)),
        );

        update_option('tradepilot_ai_settings', $settings, false);

        return $settings;
    }

    /**
     * TradePilot Core
     * Module: Settings Framework
     * Function: Convert service area array to textarea content.
     * Version: 0.4.0
     */
    public static function service_areas_text() {
        $areas = self::get('service_areas', array());
        return is_array($areas) ? implode("\n", $areas) : '';
    }
}

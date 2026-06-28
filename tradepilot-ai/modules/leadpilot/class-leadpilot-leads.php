<?php
/**
 * TradePilot AI
 * Module: LeadPilot Data Layer
 * Function: Saves and reads leads from the custom TradePilot lead table.
 * Version: 1.0.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class LeadPilot_Leads {

    /**
     * LeadPilot
     * Module: Lead Data
     * Function: Insert a new lead into the custom leads table.
     * Version: 1.0.0
     */
    public static function create($data) {
        global $wpdb;

        if (!class_exists('TradePilot_Database')) {
            require_once TRADEPILOT_AI_PATH . 'includes/database/class-tradepilot-database.php';
        }

        $now = current_time('mysql');

        $record = array(
            'source'         => self::clean($data, 'source', 'website'),
            'status'         => 'new',
            'temperature'    => 'unscored',
            'score'          => null,
            'service_type'   => self::clean($data, 'service_type'),
            'customer_name'  => self::clean($data, 'customer_name'),
            'customer_email' => self::email($data, 'customer_email'),
            'customer_phone' => self::clean($data, 'customer_phone'),
            'postcode'       => strtoupper(self::clean($data, 'postcode')),
            'budget_range'   => self::clean($data, 'budget_range'),
            'urgency'        => self::clean($data, 'urgency'),
            'description'    => self::textarea($data, 'description'),
            'meta'           => wp_json_encode(isset($data['meta']) && is_array($data['meta']) ? $data['meta'] : array()),
            'assigned_to'    => null,
            'created_at'     => $now,
            'updated_at'     => $now,
        );

        $inserted = $wpdb->insert(
            TradePilot_Database::leads_table(),
            $record,
            array('%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s')
        );

        if (false === $inserted) {
            return false;
        }

        return (int) $wpdb->insert_id;
    }

    /**
     * LeadPilot
     * Module: Lead Data
     * Function: Return recent leads for admin display.
     * Version: 1.0.0
     */
    public static function recent($limit = 50) {
        global $wpdb;

        $limit = absint($limit);
        $limit = $limit > 0 ? min($limit, 100) : 50;

        return $wpdb->get_results(
            $wpdb->prepare(
                'SELECT * FROM ' . TradePilot_Database::leads_table() . ' ORDER BY id DESC LIMIT %d',
                $limit
            ),
            ARRAY_A
        );
    }

    /**
     * LeadPilot
     * Module: Lead Data
     * Function: Return one lead by ID.
     * Version: 1.0.0
     */
    public static function get($lead_id) {
        global $wpdb;

        $lead_id = absint($lead_id);

        if (!$lead_id) {
            return null;
        }

        return $wpdb->get_row(
            $wpdb->prepare(
                'SELECT * FROM ' . TradePilot_Database::leads_table() . ' WHERE id = %d',
                $lead_id
            ),
            ARRAY_A
        );
    }

    /**
     * LeadPilot
     * Module: Lead Data
     * Function: Get a sanitised text value from incoming data.
     * Version: 1.0.0
     */
    private static function clean($data, $key, $default = '') {
        return isset($data[$key]) ? sanitize_text_field(wp_unslash($data[$key])) : $default;
    }

    /**
     * LeadPilot
     * Module: Lead Data
     * Function: Get a sanitised textarea value from incoming data.
     * Version: 1.0.0
     */
    private static function textarea($data, $key) {
        return isset($data[$key]) ? sanitize_textarea_field(wp_unslash($data[$key])) : '';
    }

    /**
     * LeadPilot
     * Module: Lead Data
     * Function: Get a sanitised email value from incoming data.
     * Version: 1.0.0
     */
    private static function email($data, $key) {
        $email = isset($data[$key]) ? sanitize_email(wp_unslash($data[$key])) : '';
        return is_email($email) ? $email : '';
    }
}

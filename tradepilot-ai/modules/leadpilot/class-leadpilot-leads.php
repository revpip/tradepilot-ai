<?php
/**
 * TradePilot AI
 * Module: LeadPilot Data Layer
 * Function: Saves, reads and filters leads from the custom TradePilot lead table.
 * Version: 1.4.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class LeadPilot_Leads {

    public static function create($data) {
        global $wpdb;

        if (!class_exists('TradePilot_Database')) {
            require_once TRADEPILOT_AI_PATH . 'includes/database/class-tradepilot-database.php';
        }

        $now = current_time('mysql');
        $meta = isset($data['meta']) && is_array($data['meta']) ? $data['meta'] : array();
        $meta['dynamic_answers'] = array(
            'property_type' => self::clean($data, 'property_type'),
            'issue_type' => self::clean($data, 'issue_type'),
            'preferred_contact_time' => self::clean($data, 'preferred_contact_time'),
        );

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
            'meta'           => wp_json_encode($meta),
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

    public static function recent($limit = 50) {
        return self::query(array(), $limit);
    }

    public static function query($filters = array(), $limit = 100) {
        global $wpdb;

        $where = array('1=1');
        $args = array();

        if (!empty($filters['status'])) {
            $where[] = 'status = %s';
            $args[] = sanitize_key($filters['status']);
        }

        if (!empty($filters['service_type'])) {
            $where[] = 'service_type = %s';
            $args[] = sanitize_text_field($filters['service_type']);
        }

        if (!empty($filters['urgency'])) {
            $where[] = 'urgency = %s';
            $args[] = sanitize_text_field($filters['urgency']);
        }

        $limit = max(1, min(absint($limit), 200));
        $sql = 'SELECT * FROM ' . TradePilot_Database::leads_table() . ' WHERE ' . implode(' AND ', $where) . ' ORDER BY id DESC LIMIT %d';
        $args[] = $limit;

        return $wpdb->get_results($wpdb->prepare($sql, $args), ARRAY_A);
    }

    public static function get($lead_id) {
        global $wpdb;

        $lead_id = absint($lead_id);
        if (!$lead_id) {
            return null;
        }

        return $wpdb->get_row(
            $wpdb->prepare('SELECT * FROM ' . TradePilot_Database::leads_table() . ' WHERE id = %d', $lead_id),
            ARRAY_A
        );
    }

    public static function meta($lead) {
        $meta = !empty($lead['meta']) ? json_decode($lead['meta'], true) : array();
        return is_array($meta) ? $meta : array();
    }

    private static function clean($data, $key, $default = '') {
        return isset($data[$key]) ? sanitize_text_field(wp_unslash($data[$key])) : $default;
    }

    private static function textarea($data, $key) {
        return isset($data[$key]) ? sanitize_textarea_field(wp_unslash($data[$key])) : '';
    }

    private static function email($data, $key) {
        $email = isset($data[$key]) ? sanitize_email(wp_unslash($data[$key])) : '';
        return is_email($email) ? $email : '';
    }
}

<?php
/**
 * TradePilot Core
 * Module: Audit Log
 * Function: Provides database-backed logging for important platform events.
 * Version: 0.4.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class TradePilot_Audit_Log {

    /**
     * TradePilot Core
     * Module: Audit Log
     * Function: Record an audit event to the custom audit table.
     * Version: 0.4.0
     */
    public static function record($event_type, $message, $context = array()) {
        global $wpdb;

        $event_type = sanitize_key($event_type);
        $message    = sanitize_text_field($message);

        if (!is_array($context)) {
            $context = array();
        }

        if (!class_exists('TradePilot_Database')) {
            require_once TRADEPILOT_AI_PATH . 'includes/database/class-tradepilot-database.php';
        }

        $wpdb->insert(
            TradePilot_Database::audit_table(),
            array(
                'event_type' => $event_type,
                'message'    => $message,
                'context'    => wp_json_encode($context),
                'user_id'    => get_current_user_id(),
                'ip_address' => self::get_ip_address(),
                'created_at' => current_time('mysql'),
            ),
            array('%s', '%s', '%s', '%d', '%s', '%s')
        );
    }

    /**
     * TradePilot Core
     * Module: Audit Log
     * Function: Return recent audit events for the logs screen.
     * Version: 0.4.0
     */
    public static function recent($limit = 50) {
        global $wpdb;

        if (!class_exists('TradePilot_Database')) {
            require_once TRADEPILOT_AI_PATH . 'includes/database/class-tradepilot-database.php';
        }

        $limit = absint($limit);
        $limit = $limit > 0 ? min($limit, 100) : 50;

        return $wpdb->get_results(
            $wpdb->prepare(
                'SELECT * FROM ' . TradePilot_Database::audit_table() . ' ORDER BY id DESC LIMIT %d',
                $limit
            ),
            ARRAY_A
        );
    }

    /**
     * TradePilot Core
     * Module: Audit Log
     * Function: Resolve IP address safely for audit context.
     * Version: 0.4.0
     */
    private static function get_ip_address() {
        $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';
        return substr($ip, 0, 45);
    }
}

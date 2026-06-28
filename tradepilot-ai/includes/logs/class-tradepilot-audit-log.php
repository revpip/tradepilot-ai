<?php
/**
 * TradePilot Core
 * Module: Audit Log
 * Function: Provides a safe placeholder logger for future database-backed audit events.
 * Version: 0.3.0
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
     * Function: Record an audit event to the WordPress debug log when enabled.
     * Version: 0.3.0
     */
    public static function record($event_type, $message, $context = array()) {
        $event_type = sanitize_key($event_type);
        $message    = sanitize_text_field($message);

        if (!is_array($context)) {
            $context = array();
        }

        /**
         * Database persistence arrives in TP-CORE-0.4.0.
         * This method gives the rest of the platform a stable logging interface now.
         */
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[TradePilot AI][' . $event_type . '] ' . $message); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
        }
    }
}

<?php
/**
 * TradePilot Core
 * Module: Database Installer
 * Function: Creates and upgrades custom TradePilot database tables.
 * Version: 0.4.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class TradePilot_Database {

    /**
     * TradePilot Core
     * Module: Database Installer
     * Function: Install or upgrade all current platform tables.
     * Version: 0.4.0
     */
    public static function install() {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset_collate = $wpdb->get_charset_collate();
        $audit_table     = self::audit_table();
        $leads_table     = self::leads_table();

        $audit_sql = "CREATE TABLE {$audit_table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            event_type VARCHAR(80) NOT NULL,
            message TEXT NOT NULL,
            context LONGTEXT NULL,
            user_id BIGINT UNSIGNED NULL,
            ip_address VARCHAR(45) NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY  (id),
            KEY event_type (event_type),
            KEY user_id (user_id),
            KEY created_at (created_at)
        ) {$charset_collate};";

        $leads_sql = "CREATE TABLE {$leads_table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            source VARCHAR(80) NULL,
            status VARCHAR(40) NOT NULL DEFAULT 'new',
            temperature VARCHAR(20) NOT NULL DEFAULT 'unscored',
            score TINYINT UNSIGNED NULL,
            service_type VARCHAR(120) NULL,
            customer_name VARCHAR(190) NULL,
            customer_email VARCHAR(190) NULL,
            customer_phone VARCHAR(80) NULL,
            postcode VARCHAR(20) NULL,
            budget_range VARCHAR(80) NULL,
            urgency VARCHAR(80) NULL,
            description LONGTEXT NULL,
            meta LONGTEXT NULL,
            assigned_to BIGINT UNSIGNED NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY  (id),
            KEY status (status),
            KEY temperature (temperature),
            KEY score (score),
            KEY service_type (service_type),
            KEY postcode (postcode),
            KEY created_at (created_at)
        ) {$charset_collate};";

        dbDelta($audit_sql);
        dbDelta($leads_sql);

        update_option('tradepilot_ai_db_version', '0.4.0', false);
    }

    /**
     * TradePilot Core
     * Module: Database Installer
     * Function: Return audit table name.
     * Version: 0.4.0
     */
    public static function audit_table() {
        global $wpdb;
        return $wpdb->prefix . 'tradepilot_audit_log';
    }

    /**
     * TradePilot Core
     * Module: Database Installer
     * Function: Return leads table name.
     * Version: 0.4.0
     */
    public static function leads_table() {
        global $wpdb;
        return $wpdb->prefix . 'tradepilot_leads';
    }
}

<?php
/**
 * TradePilot AI
 * Module: ReplyPilot Scheduler
 * Function: Provides a foundation for scheduled follow-up records.
 * Version: 1.1.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class ReplyPilot_Scheduler {

    public static function schedule($lead_id, $template_key, $days_after = 2) {
        $lead = LeadPilot_Leads::get($lead_id);
        if (!$lead) { return false; }

        $meta = LeadPilot_Leads::meta($lead);
        if (empty($meta['replypilot_queue']) || !is_array($meta['replypilot_queue'])) {
            $meta['replypilot_queue'] = array();
        }

        $meta['replypilot_queue'][] = array(
            'template' => sanitize_key($template_key),
            'status' => 'scheduled',
            'scheduled_for' => gmdate('Y-m-d H:i:s', strtotime('+' . absint($days_after) . ' days')),
            'created_at' => current_time('mysql'),
            'created_by' => get_current_user_id(),
        );

        return self::save_meta($lead_id, $meta);
    }

    public static function queue($lead) {
        $meta = LeadPilot_Leads::meta($lead);
        return isset($meta['replypilot_queue']) && is_array($meta['replypilot_queue']) ? $meta['replypilot_queue'] : array();
    }

    private static function save_meta($lead_id, $meta) {
        global $wpdb;
        return false !== $wpdb->update(
            TradePilot_Database::leads_table(),
            array('meta' => wp_json_encode($meta), 'updated_at' => current_time('mysql')),
            array('id' => absint($lead_id)),
            array('%s', '%s'),
            array('%d')
        );
    }
}

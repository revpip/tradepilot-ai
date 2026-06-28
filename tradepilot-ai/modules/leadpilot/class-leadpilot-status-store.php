<?php
/**
 * TradePilot AI
 * Module: LeadPilot Status Store
 * Function: Persists lead status changes and admin notes to the lead table metadata.
 * Version: 1.2.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class LeadPilot_Status_Store {

    public static function statuses() {
        return array('new', 'contacted', 'quoted', 'booked', 'lost', 'archived');
    }

    public static function save($lead_id, $status, $note) {
        global $wpdb;

        $lead_id = absint($lead_id);
        $lead = LeadPilot_Leads::get($lead_id);

        if (!$lead) {
            return false;
        }

        $status = sanitize_key($status);
        if (!in_array($status, self::statuses(), true)) {
            $status = 'new';
        }

        $meta = !empty($lead['meta']) ? json_decode($lead['meta'], true) : array();
        if (!is_array($meta)) {
            $meta = array();
        }

        $note = sanitize_textarea_field($note);
        if ('' !== trim($note)) {
            if (empty($meta['admin_notes']) || !is_array($meta['admin_notes'])) {
                $meta['admin_notes'] = array();
            }

            $meta['admin_notes'][] = array(
                'note' => $note,
                'user_id' => get_current_user_id(),
                'created_at' => current_time('mysql'),
            );
        }

        return false !== $wpdb->update(
            TradePilot_Database::leads_table(),
            array(
                'status' => $status,
                'meta' => wp_json_encode($meta),
                'updated_at' => current_time('mysql'),
            ),
            array('id' => $lead_id),
            array('%s', '%s', '%s'),
            array('%d')
        );
    }
}

<?php
/**
 * TradePilot AI
 * Module: LeadPilot Admin Actions
 * Function: Saves lead admin actions through safe WordPress handlers.
 * Version: 1.1.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class LeadPilot_Admin_Actions {

    public static function init() {
        add_action('admin_post_leadpilot_save_lead_action', array(__CLASS__, 'save'));
    }

    public static function save() {
        TradePilot_Security::verify_admin_action('leadpilot_save_lead_action', 'leadpilot_action_nonce');

        $lead_id = isset($_POST['lead_id']) ? absint($_POST['lead_id']) : 0;
        $status = isset($_POST['lead_status']) ? sanitize_key(wp_unslash($_POST['lead_status'])) : 'new';
        $note = isset($_POST['admin_note']) ? sanitize_textarea_field(wp_unslash($_POST['admin_note'])) : '';

        update_option('leadpilot_last_action_' . $lead_id, array(
            'status' => $status,
            'note' => $note,
            'user_id' => get_current_user_id(),
            'created_at' => current_time('mysql'),
        ), false);

        TradePilot_Audit_Log::record('lead_action_saved', 'LeadPilot admin action saved.', array('lead_id' => $lead_id, 'status' => $status));

        wp_safe_redirect(add_query_arg(array('page' => 'tradepilot-ai-leads', 'lead_id' => $lead_id, 'tradepilot_status' => 'saved'), admin_url('admin.php')));
        exit;
    }
}

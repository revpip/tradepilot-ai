<?php
/**
 * TradePilot AI
 * Module: ReplyPilot
 * Function: Sends templated lead replies and stores message events.
 * Version: 1.0.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class ReplyPilot {

    public static function init() {
        add_action('leadpilot_after_lead_created', array(__CLASS__, 'auto_acknowledge'), 20, 1);
        add_action('admin_post_replypilot_send_template', array(__CLASS__, 'send_template_action'));
    }

    public static function auto_acknowledge($lead_id) {
        $lead = LeadPilot_Leads::get($lead_id);
        if (!$lead || empty($lead['customer_email'])) {
            return false;
        }
        return self::send($lead_id, 'new_lead_customer');
    }

    public static function send($lead_id, $template_key) {
        $lead = LeadPilot_Leads::get($lead_id);
        if (!$lead || empty($lead['customer_email']) || !is_email($lead['customer_email'])) {
            return false;
        }

        $message = ReplyPilot_Templates::render($template_key, $lead);
        if (!$message) {
            return false;
        }

        $sent = wp_mail($lead['customer_email'], $message['subject'], $message['body']);
        self::record_message($lead, $template_key, $message, $sent);

        return $sent;
    }

    public static function send_template_action() {
        TradePilot_Security::verify_admin_action('replypilot_send_template', 'replypilot_template_nonce');

        $lead_id = isset($_POST['lead_id']) ? absint($_POST['lead_id']) : 0;
        $template_key = isset($_POST['template_key']) ? sanitize_key(wp_unslash($_POST['template_key'])) : '';

        self::send($lead_id, $template_key);

        wp_safe_redirect(add_query_arg(array('page' => 'tradepilot-ai-leads', 'lead_id' => $lead_id, 'tradepilot_status' => 'message_sent'), admin_url('admin.php')));
        exit;
    }

    private static function record_message($lead, $template_key, $message, $sent) {
        global $wpdb;

        $meta = LeadPilot_Leads::meta($lead);
        if (empty($meta['replypilot_messages']) || !is_array($meta['replypilot_messages'])) {
            $meta['replypilot_messages'] = array();
        }

        $meta['replypilot_messages'][] = array(
            'template' => sanitize_key($template_key),
            'subject' => sanitize_text_field($message['subject']),
            'sent' => (bool) $sent,
            'created_at' => current_time('mysql'),
        );

        $wpdb->update(
            TradePilot_Database::leads_table(),
            array('meta' => wp_json_encode($meta), 'updated_at' => current_time('mysql')),
            array('id' => absint($lead['id'])),
            array('%s', '%s'),
            array('%d')
        );

        TradePilot_Audit_Log::record('replypilot_message', 'ReplyPilot message processed.', array('lead_id' => $lead['id'], 'template' => $template_key, 'sent' => (bool) $sent));
    }
}

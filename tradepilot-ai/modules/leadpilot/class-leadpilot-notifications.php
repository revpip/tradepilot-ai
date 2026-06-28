<?php
/**
 * TradePilot AI
 * Module: LeadPilot Notifications
 * Function: Sends business and customer acknowledgement emails.
 * Version: 1.1.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class LeadPilot_Notifications {

    public static function send_new_lead_notifications($lead_id) {
        $lead = LeadPilot_Leads::get($lead_id);

        if (!$lead) {
            return;
        }

        self::send_business_email($lead);
        self::send_customer_email($lead);
    }

    private static function send_business_email($lead) {
        $to = TradePilot_Settings::get('support_email', get_option('admin_email'));
        $subject = 'New TradePilot lead #' . absint($lead['id']) . ' - ' . $lead['service_type'];
        $body = "A new enquiry has arrived.\n\n";
        $body .= 'Customer: ' . $lead['customer_name'] . "\n";
        $body .= 'Phone: ' . $lead['customer_phone'] . "\n";
        $body .= 'Email: ' . $lead['customer_email'] . "\n";
        $body .= 'Postcode: ' . $lead['postcode'] . "\n";
        $body .= 'Service: ' . $lead['service_type'] . "\n";
        $body .= 'Budget: ' . $lead['budget_range'] . "\n";
        $body .= 'Urgency: ' . $lead['urgency'] . "\n\n";
        $body .= "Description:\n" . $lead['description'] . "\n";

        wp_mail($to, $subject, $body);
    }

    private static function send_customer_email($lead) {
        if (empty($lead['customer_email']) || !is_email($lead['customer_email'])) {
            return;
        }

        $business = TradePilot_Settings::get('business_name', 'the team');
        $subject = 'We have received your enquiry';
        $body = 'Hello ' . $lead['customer_name'] . ",\n\n";
        $body .= 'Thank you for your enquiry. We have received the details and will review them shortly.' . "\n\n";
        $body .= 'Service: ' . $lead['service_type'] . "\n";
        $body .= 'Urgency: ' . $lead['urgency'] . "\n\n";
        $body .= 'Kind regards,' . "\n" . $business;

        wp_mail($lead['customer_email'], $subject, $body);
    }
}

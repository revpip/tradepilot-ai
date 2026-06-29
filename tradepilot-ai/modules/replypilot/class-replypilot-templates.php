<?php
/**
 * TradePilot AI
 * Module: ReplyPilot Templates
 * Function: Provides message templates and merge-tag rendering.
 * Version: 1.0.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class ReplyPilot_Templates {

    public static function defaults() {
        return array(
            'new_lead_customer' => array(
                'label' => 'New lead customer acknowledgement',
                'subject' => 'We have received your enquiry',
                'body' => "Hello {customer_name},\n\nWe have received your enquiry about {service_type}. The details are now with the team.\n\nUrgency: {urgency}\nPostcode: {postcode}\n\nKind regards,\n{business_name}",
            ),
            'missing_photos' => array(
                'label' => 'Missing photos request',
                'subject' => 'Photos would help us assess this',
                'body' => "Hello {customer_name},\n\nA few photos would help us understand the job properly and respond more accurately.\n\nKind regards,\n{business_name}",
            ),
            'quote_follow_up' => array(
                'label' => 'Quote follow-up',
                'subject' => 'Checking in about your enquiry',
                'body' => "Hello {customer_name},\n\nWe are checking whether you have any questions about your {service_type} enquiry.\n\nKind regards,\n{business_name}",
            ),
            'cold_lead_qualify' => array(
                'label' => 'Cold lead qualification',
                'subject' => 'A few extra details needed',
                'body' => "Hello {customer_name},\n\nCould you confirm your budget, timing and any access details for the job?\n\nKind regards,\n{business_name}",
            ),
        );
    }

    public static function get_all() {
        $saved = get_option('replypilot_templates', array());
        return is_array($saved) ? wp_parse_args($saved, self::defaults()) : self::defaults();
    }

    public static function get($key) {
        $templates = self::get_all();
        return isset($templates[$key]) ? $templates[$key] : null;
    }

    public static function render($template_key, $lead) {
        $template = self::get($template_key);
        if (!$template) { return null; }
        $tags = self::tags($lead);
        return array('subject' => strtr($template['subject'], $tags), 'body' => strtr($template['body'], $tags));
    }

    private static function tags($lead) {
        return array(
            '{customer_name}' => isset($lead['customer_name']) ? $lead['customer_name'] : '',
            '{service_type}' => isset($lead['service_type']) ? $lead['service_type'] : '',
            '{urgency}' => isset($lead['urgency']) ? $lead['urgency'] : '',
            '{postcode}' => isset($lead['postcode']) ? $lead['postcode'] : '',
            '{budget_range}' => isset($lead['budget_range']) ? $lead['budget_range'] : '',
            '{business_name}' => TradePilot_Settings::get('business_name', get_bloginfo('name')),
        );
    }
}

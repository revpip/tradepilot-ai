<?php
/**
 * TradePilot AI
 * Module: LeadPilot AI Engine
 * Function: Scores leads, creates summaries and recommends next actions.
 * Version: 1.0.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class LeadPilot_AI {

    public static function init() {
        add_action('leadpilot_after_lead_created', array(__CLASS__, 'score_lead'), 10, 1);
    }

    public static function score_lead($lead_id) {
        global $wpdb;

        $lead = LeadPilot_Leads::get($lead_id);
        if (!$lead) {
            return false;
        }

        $score = self::calculate_score($lead);
        $temperature = self::temperature($score);
        $summary = self::summary($lead, $score, $temperature);
        $next_action = self::next_action($lead, $score, $temperature);

        $meta = LeadPilot_Leads::meta($lead);
        $meta['ai'] = array(
            'score' => $score,
            'temperature' => $temperature,
            'summary' => $summary,
            'next_action' => $next_action,
            'generated_at' => current_time('mysql'),
            'engine' => 'rule_based_v1',
        );

        $updated = $wpdb->update(
            TradePilot_Database::leads_table(),
            array(
                'score' => $score,
                'temperature' => $temperature,
                'meta' => wp_json_encode($meta),
                'updated_at' => current_time('mysql'),
            ),
            array('id' => absint($lead_id)),
            array('%d', '%s', '%s', '%s'),
            array('%d')
        );

        if (false !== $updated) {
            TradePilot_Audit_Log::record('lead_ai_scored', 'LeadPilot AI scored a lead.', array('lead_id' => $lead_id, 'score' => $score, 'temperature' => $temperature));
        }

        return false !== $updated;
    }

    private static function calculate_score($lead) {
        $score = 20;

        if (!empty($lead['customer_phone'])) { $score += 10; }
        if (!empty($lead['customer_email'])) { $score += 5; }
        if (!empty($lead['postcode'])) { $score += 10; }
        if (strlen((string) $lead['description']) > 80) { $score += 10; }

        if ('Emergency' === $lead['urgency']) { $score += 20; }
        if ('Within 48 hours' === $lead['urgency']) { $score += 15; }
        if ('This week' === $lead['urgency']) { $score += 10; }

        if ('£15,000+' === $lead['budget_range']) { $score += 20; }
        if ('£5,000 - £15,000' === $lead['budget_range']) { $score += 15; }
        if ('£1,000 - £5,000' === $lead['budget_range']) { $score += 10; }
        if ('Under £250' === $lead['budget_range']) { $score -= 5; }

        $high_value = array('Bathroom', 'Kitchen', 'Roofing', 'Gas / Boiler');
        if (in_array($lead['service_type'], $high_value, true)) { $score += 10; }

        $meta = LeadPilot_Leads::meta($lead);
        if (!empty($meta['uploads']) && is_array($meta['uploads'])) { $score += 10; }

        return max(0, min(100, $score));
    }

    private static function temperature($score) {
        if ($score >= 80) { return 'hot'; }
        if ($score >= 50) { return 'warm'; }
        return 'cold';
    }

    private static function summary($lead, $score, $temperature) {
        return sprintf(
            '%s lead scored %d/100 for %s in %s. Budget: %s. Urgency: %s.',
            ucfirst($temperature),
            absint($score),
            $lead['service_type'],
            $lead['postcode'],
            $lead['budget_range'],
            $lead['urgency']
        );
    }

    private static function next_action($lead, $score, $temperature) {
        if ('hot' === $temperature) {
            return 'Call immediately and prioritise for quote or site visit.';
        }
        if ('warm' === $temperature) {
            return 'Contact today, confirm details and request any missing photos or timing information.';
        }
        return 'Send a polite follow-up and qualify budget, timing and seriousness before committing time.';
    }
}

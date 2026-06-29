<?php
/**
 * TradePilot AI
 * Module: ReplyPilot Scheduler
 * Function: Schedules and processes follow-up records.
 * Version: 1.2.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class ReplyPilot_Scheduler {

    const CRON_HOOK = 'replypilot_process_followups';

    public static function init() {
        add_action(self::CRON_HOOK, array(__CLASS__, 'process_due'));
        if (!wp_next_scheduled(self::CRON_HOOK)) {
            wp_schedule_event(time() + HOUR_IN_SECONDS, 'hourly', self::CRON_HOOK);
        }
    }

    public static function schedule($lead_id, $template_key, $days_after = 2) {
        $lead = LeadPilot_Leads::get($lead_id);
        if (!$lead) { return false; }

        $meta = LeadPilot_Leads::meta($lead);
        if (empty($meta['replypilot_queue']) || !is_array($meta['replypilot_queue'])) {
            $meta['replypilot_queue'] = array();
        }

        $meta['replypilot_queue'][] = array(
            'id' => uniqid('rp_', true),
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

    public static function process_due($limit = 25) {
        global $wpdb;

        $limit = max(1, min(absint($limit), 100));
        $leads = $wpdb->get_results(
            $wpdb->prepare('SELECT * FROM ' . TradePilot_Database::leads_table() . ' WHERE meta LIKE %s ORDER BY id ASC LIMIT %d', '%replypilot_queue%', $limit),
            ARRAY_A
        );

        $processed = 0;
        foreach ($leads as $lead) {
            $meta = LeadPilot_Leads::meta($lead);
            if (empty($meta['replypilot_queue']) || !is_array($meta['replypilot_queue'])) { continue; }

            $changed = false;
            foreach ($meta['replypilot_queue'] as $index => $item) {
                if (empty($item['status']) || 'scheduled' !== $item['status']) { continue; }
                if (empty($item['scheduled_for']) || strtotime($item['scheduled_for']) > time()) { continue; }

                $sent = ReplyPilot::send((int) $lead['id'], $item['template']);
                $meta['replypilot_queue'][$index]['status'] = $sent ? 'sent' : 'failed';
                $meta['replypilot_queue'][$index]['processed_at'] = current_time('mysql');
                $changed = true;
                $processed++;
            }

            if ($changed) {
                self::save_meta((int) $lead['id'], $meta);
            }
        }

        TradePilot_Audit_Log::record('replypilot_cron_processed', 'ReplyPilot processed scheduled follow-ups.', array('processed' => $processed));
        return $processed;
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

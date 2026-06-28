<?php
/**
 * TradePilot AI
 * Module: LeadPilot Upload Helper
 * Function: Handles public image attachments for lead enquiries.
 * Version: 1.2.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class LeadPilot_Upload_Helper {

    public static function collect($field_name = 'lead_photos') {
        if (empty($_FILES[$field_name])) {
            return array();
        }

        require_once ABSPATH . 'wp-admin/includes/file.php';

        $incoming = $_FILES[$field_name];
        $items = self::split($incoming);
        $saved = array();

        foreach ($items as $item) {
            if (empty($item['name']) || empty($item['tmp_name'])) {
                continue;
            }

            if (!self::is_allowed($item)) {
                continue;
            }

            $result = wp_handle_upload($item, array('test_form' => false));

            if (!empty($result['url']) && empty($result['error'])) {
                $saved[] = array(
                    'url' => esc_url_raw($result['url']),
                    'type' => sanitize_text_field($result['type']),
                );
            }
        }

        return $saved;
    }

    private static function split($incoming) {
        if (!is_array($incoming['name'])) {
            return array($incoming);
        }

        $items = array();
        foreach ($incoming['name'] as $i => $name) {
            $items[] = array(
                'name' => $name,
                'type' => $incoming['type'][$i],
                'tmp_name' => $incoming['tmp_name'][$i],
                'error' => $incoming['error'][$i],
                'size' => $incoming['size'][$i],
            );
        }

        return $items;
    }

    private static function is_allowed($item) {
        if (!empty($item['error'])) {
            return false;
        }

        if (!empty($item['size']) && (int) $item['size'] > 5242880) {
            return false;
        }

        $check = wp_check_filetype_and_ext($item['tmp_name'], $item['name']);
        $allowed = array('jpg', 'jpeg', 'png', 'webp');

        return !empty($check['ext']) && in_array(strtolower($check['ext']), $allowed, true);
    }
}

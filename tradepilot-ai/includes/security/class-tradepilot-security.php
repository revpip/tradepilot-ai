<?php
/**
 * TradePilot Core
 * Module: Security Helpers
 * Function: Centralises nonce, capability and redirect helpers.
 * Version: 0.4.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class TradePilot_Security {

    /**
     * TradePilot Core
     * Module: Security Helpers
     * Function: Verify admin capability and nonce before saving settings.
     * Version: 0.4.0
     */
    public static function verify_admin_action($nonce_action, $nonce_field) {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to perform this TradePilot AI action.', 'tradepilot-ai'));
        }

        $nonce = isset($_POST[$nonce_field]) ? sanitize_text_field(wp_unslash($_POST[$nonce_field])) : '';

        if (!wp_verify_nonce($nonce, $nonce_action)) {
            wp_die(esc_html__('Security check failed. Please reload the page and try again.', 'tradepilot-ai'));
        }
    }

    /**
     * TradePilot Core
     * Module: Security Helpers
     * Function: Redirect back to an admin page with a status flag.
     * Version: 0.4.0
     */
    public static function redirect_admin($page, $status = 'saved') {
        wp_safe_redirect(
            add_query_arg(
                array(
                    'page' => sanitize_key($page),
                    'tradepilot_status' => sanitize_key($status),
                ),
                admin_url('admin.php')
            )
        );
        exit;
    }
}

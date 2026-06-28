<?php
/**
 * TradePilot AI
 * Module: LeadPilot
 * Function: Smart lead capture and qualification module bootstrap.
 * Version: 1.2.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/class-leadpilot-leads.php';
require_once __DIR__ . '/class-leadpilot-upload-helper.php';
require_once __DIR__ . '/class-leadpilot-status-store.php';
require_once __DIR__ . '/class-leadpilot-notifications.php';
require_once __DIR__ . '/class-leadpilot.php';
require_once __DIR__ . '/class-leadpilot-admin.php';
require_once __DIR__ . '/class-leadpilot-admin-actions.php';

LeadPilot::init();

if (is_admin()) {
    LeadPilot_Admin::init();
    LeadPilot_Admin_Actions::init();
}

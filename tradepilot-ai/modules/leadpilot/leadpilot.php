<?php
/**
 * TradePilot AI
 * Module: LeadPilot
 * Function: Smart lead capture and qualification module bootstrap.
 * Version: 1.0.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/class-leadpilot-leads.php';
require_once __DIR__ . '/class-leadpilot.php';
require_once __DIR__ . '/class-leadpilot-admin.php';

LeadPilot::init();

if (is_admin()) {
    LeadPilot_Admin::init();
}

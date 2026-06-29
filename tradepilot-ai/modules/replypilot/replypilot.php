<?php
/**
 * TradePilot AI
 * Module: ReplyPilot
 * Function: Automated response and follow-up module bootstrap.
 * Version: 1.0.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/class-replypilot-templates.php';
require_once __DIR__ . '/class-replypilot.php';
require_once __DIR__ . '/class-replypilot-admin.php';

ReplyPilot::init();

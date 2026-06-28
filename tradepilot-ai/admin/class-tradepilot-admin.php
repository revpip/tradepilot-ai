<?php
/**
 * TradePilot Core
 * Module: Admin Framework
 * Function: Creates admin menus, dashboard shell, settings and logs screens.
 * Version: 0.3.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class TradePilot_Admin {

    /**
     * TradePilot Core
     * Module: Admin Framework
     * Function: Register admin hooks.
     * Version: 0.3.0
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'register_menu'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
    }

    /**
     * TradePilot Core
     * Module: Admin Framework
     * Function: Load admin-only assets.
     * Version: 0.3.0
     */
    public static function enqueue_assets($hook) {
        if (false === strpos((string) $hook, 'tradepilot-ai')) {
            return;
        }

        wp_enqueue_style(
            'tradepilot-ai-admin',
            TRADEPILOT_AI_URL . 'assets/admin.css',
            array(),
            TRADEPILOT_AI_VERSION
        );
    }

    /**
     * TradePilot Core
     * Module: Admin Framework
     * Function: Register primary and sub-menu pages.
     * Version: 0.3.0
     */
    public static function register_menu() {
        add_menu_page(
            'TradePilot AI',
            'TradePilot AI',
            'manage_options',
            'tradepilot-ai',
            array(__CLASS__, 'render_dashboard'),
            'dashicons-admin-generic',
            26
        );

        $pages = array(
            array('Dashboard', 'Dashboard', 'tradepilot-ai', 'render_dashboard'),
            array('Leads', 'Leads', 'tradepilot-ai-leads', 'render_placeholder'),
            array('CRM', 'CRM', 'tradepilot-ai-crm', 'render_placeholder'),
            array('Quotes', 'Quotes', 'tradepilot-ai-quotes', 'render_placeholder'),
            array('Routing', 'Routing', 'tradepilot-ai-routing', 'render_placeholder'),
            array('Reviews', 'Reviews', 'tradepilot-ai-reviews', 'render_placeholder'),
            array('Analytics', 'Analytics', 'tradepilot-ai-analytics', 'render_placeholder'),
            array('Modules', 'Modules', 'tradepilot-ai-modules', 'render_modules'),
            array('Settings', 'Settings', 'tradepilot-ai-settings', 'render_settings'),
            array('Logs', 'Logs', 'tradepilot-ai-logs', 'render_logs'),
        );

        foreach ($pages as $page) {
            add_submenu_page(
                'tradepilot-ai',
                'TradePilot AI - ' . $page[0],
                $page[1],
                'manage_options',
                $page[2],
                array(__CLASS__, $page[3])
            );
        }
    }

    /**
     * TradePilot Core
     * Module: Admin Dashboard
     * Function: Render first dashboard cards.
     * Version: 0.3.0
     */
    public static function render_dashboard() {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access TradePilot AI.', 'tradepilot-ai'));
        }

        $modules = TradePilot_Modules::get_modules();
        $active  = array_filter($modules, static function ($module) {
            return !empty($module['enabled']);
        });
        ?>
        <div class="wrap tradepilot-admin">
            <h1>TradePilot AI</h1>
            <p class="tradepilot-lede">The intelligent operating system for trade businesses.</p>

            <div class="tradepilot-card-grid">
                <?php self::dashboard_card('New Leads', '0', 'LeadPilot will populate this.'); ?>
                <?php self::dashboard_card('Hot Leads', '0', 'AI scoring comes in LeadPilot AI.'); ?>
                <?php self::dashboard_card('Quotes Pending', '0', 'QuotePilot will populate this.'); ?>
                <?php self::dashboard_card('Jobs Booked', '0', 'RoutePilot will populate this.'); ?>
                <?php self::dashboard_card('Revenue Snapshot', '£0', 'InsightPilot will populate this.'); ?>
                <?php self::dashboard_card('Active Modules', (string) count($active), 'Module registry is now live.'); ?>
            </div>
        </div>
        <?php
    }

    /**
     * TradePilot Core
     * Module: Admin Dashboard
     * Function: Render a reusable dashboard card.
     * Version: 0.3.0
     */
    private static function dashboard_card($title, $value, $note) {
        ?>
        <div class="tradepilot-card">
            <h2><?php echo esc_html($title); ?></h2>
            <strong><?php echo esc_html($value); ?></strong>
            <p><?php echo esc_html($note); ?></p>
        </div>
        <?php
    }

    /**
     * TradePilot Core
     * Module: Module Manager
     * Function: Render module status grid.
     * Version: 0.3.0
     */
    public static function render_modules() {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access TradePilot AI modules.', 'tradepilot-ai'));
        }

        $modules = TradePilot_Modules::get_modules();
        ?>
        <div class="wrap tradepilot-admin">
            <h1>TradePilot Modules</h1>
            <p class="tradepilot-lede">Core intelligence modules are registered here. Enable/disable controls arrive in the next settings build.</p>

            <div class="tradepilot-card-grid">
                <?php foreach ($modules as $key => $module) : ?>
                    <div class="tradepilot-card">
                        <h2><?php echo esc_html($module['name']); ?></h2>
                        <p><?php echo esc_html($module['description']); ?></p>
                        <p><strong>Version:</strong> <?php echo esc_html($module['version']); ?></p>
                        <p><strong>Status:</strong> <?php echo !empty($module['enabled']) ? 'Enabled' : 'Disabled'; ?></p>
                        <p><code><?php echo esc_html($key); ?></code></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * TradePilot Core
     * Module: Settings Framework
     * Function: Render first settings shell.
     * Version: 0.3.0
     */
    public static function render_settings() {
        ?>
        <div class="wrap tradepilot-admin">
            <h1>TradePilot Settings</h1>
            <p class="tradepilot-lede">Settings framework shell for business profile, service areas, AI settings and integrations.</p>
            <div class="tradepilot-card-grid">
                <?php self::dashboard_card('General', 'Ready', 'Business name, logo, support email.'); ?>
                <?php self::dashboard_card('Service Areas', 'Ready', 'Postcodes, villages, regions and territories.'); ?>
                <?php self::dashboard_card('AI Settings', 'Ready', 'Scoring thresholds and automation rules.'); ?>
                <?php self::dashboard_card('Integrations', 'Ready', 'OpenAI, email, SMS, Stripe, Meta and Google.'); ?>
            </div>
        </div>
        <?php
    }

    /**
     * TradePilot Core
     * Module: Logs Viewer
     * Function: Render first logs shell.
     * Version: 0.3.0
     */
    public static function render_logs() {
        ?>
        <div class="wrap tradepilot-admin">
            <h1>TradePilot Logs</h1>
            <p class="tradepilot-lede">Audit and system logs will appear here as the database framework is introduced.</p>
        </div>
        <?php
    }

    /**
     * TradePilot Core
     * Module: Admin Placeholders
     * Function: Render placeholder screens for future modules.
     * Version: 0.3.0
     */
    public static function render_placeholder() {
        $screen = get_current_screen();
        $title  = $screen && !empty($screen->base) ? ucwords(str_replace(array('tradepilot-ai_page_tradepilot-ai-', '-'), array('', ' '), $screen->base)) : 'TradePilot Module';
        ?>
        <div class="wrap tradepilot-admin">
            <h1><?php echo esc_html($title); ?></h1>
            <p class="tradepilot-lede">This screen is registered and ready for the next module build.</p>
        </div>
        <?php
    }
}

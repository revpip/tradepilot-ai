<?php
/**
 * TradePilot Core
 * Module: Admin Framework
 * Function: Creates admin menus, dashboard, settings, modules and logs screens.
 * Version: 0.4.0
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
     * Version: 0.4.0
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'register_menu'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
        add_action('admin_post_tradepilot_save_settings', array(__CLASS__, 'handle_save_settings'));
        add_action('admin_post_tradepilot_save_modules', array(__CLASS__, 'handle_save_modules'));
    }

    /**
     * TradePilot Core
     * Module: Admin Framework
     * Function: Load admin-only assets.
     * Version: 0.4.0
     */
    public static function enqueue_assets($hook) {
        if (false === strpos((string) $hook, 'tradepilot-ai')) {
            return;
        }

        wp_enqueue_style('tradepilot-ai-admin', TRADEPILOT_AI_URL . 'assets/admin.css', array(), TRADEPILOT_AI_VERSION);
    }

    /**
     * TradePilot Core
     * Module: Admin Framework
     * Function: Register primary and sub-menu pages.
     * Version: 0.4.0
     */
    public static function register_menu() {
        add_menu_page('TradePilot AI', 'TradePilot AI', 'manage_options', 'tradepilot-ai', array(__CLASS__, 'render_dashboard'), 'dashicons-admin-generic', 26);

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
            add_submenu_page('tradepilot-ai', 'TradePilot AI - ' . $page[0], $page[1], 'manage_options', $page[2], array(__CLASS__, $page[3]));
        }
    }

    /**
     * TradePilot Core
     * Module: Admin Notices
     * Function: Render save notices from safe redirect status flags.
     * Version: 0.4.0
     */
    private static function maybe_notice() {
        $status = isset($_GET['tradepilot_status']) ? sanitize_key(wp_unslash($_GET['tradepilot_status'])) : '';

        if ('saved' === $status) {
            echo '<div class="notice notice-success is-dismissible"><p>TradePilot AI settings saved.</p></div>';
        }
    }

    /**
     * TradePilot Core
     * Module: Admin Dashboard
     * Function: Render dashboard cards.
     * Version: 0.4.0
     */
    public static function render_dashboard() {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access TradePilot AI.', 'tradepilot-ai'));
        }

        global $wpdb;
        $modules = TradePilot_Modules::get_modules();
        $active  = array_filter($modules, static function ($module) {
            return !empty($module['enabled']);
        });

        $lead_count = 0;
        if (class_exists('TradePilot_Database')) {
            $lead_count = (int) $wpdb->get_var('SELECT COUNT(*) FROM ' . TradePilot_Database::leads_table());
        }
        ?>
        <div class="wrap tradepilot-admin">
            <?php self::maybe_notice(); ?>
            <h1>TradePilot AI</h1>
            <p class="tradepilot-lede">The intelligent operating system for trade businesses.</p>

            <div class="tradepilot-card-grid">
                <?php self::dashboard_card('New Leads', (string) $lead_count, 'LeadPilot will populate this.'); ?>
                <?php self::dashboard_card('Hot Leads', '0', 'AI scoring comes in LeadPilot AI.'); ?>
                <?php self::dashboard_card('Quotes Pending', '0', 'QuotePilot will populate this.'); ?>
                <?php self::dashboard_card('Jobs Booked', '0', 'RoutePilot will populate this.'); ?>
                <?php self::dashboard_card('Revenue Snapshot', '£0', 'InsightPilot will populate this.'); ?>
                <?php self::dashboard_card('Active Modules', (string) count($active), 'Module registry is live and configurable.'); ?>
            </div>
        </div>
        <?php
    }

    /**
     * TradePilot Core
     * Module: Admin Dashboard
     * Function: Render a reusable dashboard card.
     * Version: 0.4.0
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
     * Function: Render module enable/disable form.
     * Version: 0.4.0
     */
    public static function render_modules() {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access TradePilot AI modules.', 'tradepilot-ai'));
        }

        $modules = TradePilot_Modules::get_modules();
        ?>
        <div class="wrap tradepilot-admin">
            <?php self::maybe_notice(); ?>
            <h1>TradePilot Modules</h1>
            <p class="tradepilot-lede">Control which intelligence modules load. LeadPilot remains enabled by default while the lead funnel is built.</p>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="tradepilot_save_modules" />
                <?php wp_nonce_field('tradepilot_save_modules', 'tradepilot_modules_nonce'); ?>

                <div class="tradepilot-card-grid">
                    <?php foreach ($modules as $key => $module) : ?>
                        <div class="tradepilot-card">
                            <h2><?php echo esc_html($module['name']); ?></h2>
                            <p><?php echo esc_html($module['description']); ?></p>
                            <p><strong>Version:</strong> <?php echo esc_html($module['version']); ?></p>
                            <label>
                                <input type="checkbox" name="modules[<?php echo esc_attr($key); ?>]" value="1" <?php checked(!empty($module['enabled'])); ?> />
                                Enabled
                            </label>
                            <p><code><?php echo esc_html($key); ?></code></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php submit_button('Save Modules'); ?>
            </form>
        </div>
        <?php
    }

    /**
     * TradePilot Core
     * Module: Settings Framework
     * Function: Render nonce-protected settings form.
     * Version: 0.4.0
     */
    public static function render_settings() {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access TradePilot AI settings.', 'tradepilot-ai'));
        }

        $settings = TradePilot_Settings::get_all();
        ?>
        <div class="wrap tradepilot-admin">
            <?php self::maybe_notice(); ?>
            <h1>TradePilot Settings</h1>
            <p class="tradepilot-lede">Configure the business profile, service areas and first lead scoring thresholds.</p>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="tradepilot-form">
                <input type="hidden" name="action" value="tradepilot_save_settings" />
                <?php wp_nonce_field('tradepilot_save_settings', 'tradepilot_settings_nonce'); ?>

                <div class="tradepilot-panel">
                    <h2>General</h2>
                    <label>Business Name
                        <input type="text" name="business_name" value="<?php echo esc_attr($settings['business_name']); ?>" class="regular-text" />
                    </label>
                    <label>Support Email
                        <input type="email" name="support_email" value="<?php echo esc_attr($settings['support_email']); ?>" class="regular-text" />
                    </label>
                </div>

                <div class="tradepilot-panel">
                    <h2>Service Areas</h2>
                    <p>Add one postcode, village, town or territory per line.</p>
                    <textarea name="service_areas" rows="8" class="large-text code"><?php echo esc_textarea(TradePilot_Settings::service_areas_text()); ?></textarea>
                </div>

                <div class="tradepilot-panel">
                    <h2>AI Lead Thresholds</h2>
                    <label>
                        <input type="checkbox" name="ai_enabled" value="1" <?php checked(!empty($settings['ai_enabled'])); ?> />
                        Enable AI scoring when the AI layer is connected
                    </label>
                    <label>Hot Lead Threshold
                        <input type="number" min="0" max="100" name="hot_threshold" value="<?php echo esc_attr($settings['hot_threshold']); ?>" />
                    </label>
                    <label>Warm Lead Threshold
                        <input type="number" min="0" max="100" name="warm_threshold" value="<?php echo esc_attr($settings['warm_threshold']); ?>" />
                    </label>
                </div>

                <?php submit_button('Save Settings'); ?>
            </form>
        </div>
        <?php
    }

    /**
     * TradePilot Core
     * Module: Logs Viewer
     * Function: Render recent database-backed audit events.
     * Version: 0.4.0
     */
    public static function render_logs() {
        $logs = TradePilot_Audit_Log::recent(50);
        ?>
        <div class="wrap tradepilot-admin">
            <h1>TradePilot Logs</h1>
            <p class="tradepilot-lede">Recent platform events are recorded here for accountability and troubleshooting.</p>
            <table class="widefat striped tradepilot-table">
                <thead>
                    <tr><th>Date</th><th>Type</th><th>Message</th><th>User</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)) : ?>
                        <tr><td colspan="4">No audit events recorded yet.</td></tr>
                    <?php else : ?>
                        <?php foreach ($logs as $log) : ?>
                            <tr>
                                <td><?php echo esc_html($log['created_at']); ?></td>
                                <td><code><?php echo esc_html($log['event_type']); ?></code></td>
                                <td><?php echo esc_html($log['message']); ?></td>
                                <td><?php echo esc_html($log['user_id']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * TradePilot Core
     * Module: Settings Save Handler
     * Function: Save platform settings after nonce and capability checks.
     * Version: 0.4.0
     */
    public static function handle_save_settings() {
        TradePilot_Security::verify_admin_action('tradepilot_save_settings', 'tradepilot_settings_nonce');
        $settings = TradePilot_Settings::save_from_post($_POST);
        TradePilot_Audit_Log::record('settings_saved', 'TradePilot settings updated.', array('settings' => $settings));
        TradePilot_Security::redirect_admin('tradepilot-ai-settings');
    }

    /**
     * TradePilot Core
     * Module: Module Save Handler
     * Function: Save module states after nonce and capability checks.
     * Version: 0.4.0
     */
    public static function handle_save_modules() {
        TradePilot_Security::verify_admin_action('tradepilot_save_modules', 'tradepilot_modules_nonce');

        $enabled = isset($_POST['modules']) && is_array($_POST['modules']) ? array_map('sanitize_key', array_keys(wp_unslash($_POST['modules']))) : array();
        $modules = TradePilot_Modules::get_modules();

        foreach ($modules as $key => $module) {
            TradePilot_Modules::set_module_state($key, in_array($key, $enabled, true));
        }

        TradePilot_Audit_Log::record('modules_saved', 'TradePilot module states updated.', array('enabled' => $enabled));
        TradePilot_Security::redirect_admin('tradepilot-ai-modules');
    }

    /**
     * TradePilot Core
     * Module: Admin Placeholders
     * Function: Render placeholder screens for future modules.
     * Version: 0.4.0
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

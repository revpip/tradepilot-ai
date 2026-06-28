<?php
/**
 * TradePilot AI
 * Module: LeadPilot Admin
 * Function: Adds LeadPilot admin lead list and lead detail screens.
 * Version: 1.0.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class LeadPilot_Admin {

    /**
     * LeadPilot
     * Module: Admin
     * Function: Register admin menu override for the Leads screen.
     * Version: 1.0.0
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'register_menu'), 20);
    }

    /**
     * LeadPilot
     * Module: Admin
     * Function: Register LeadPilot admin screen under TradePilot AI.
     * Version: 1.0.0
     */
    public static function register_menu() {
        add_submenu_page(
            'tradepilot-ai',
            'TradePilot AI - Leads',
            'Leads',
            'manage_options',
            'tradepilot-ai-leads',
            array(__CLASS__, 'render_leads')
        );
    }

    /**
     * LeadPilot
     * Module: Admin
     * Function: Render recent leads or a single lead detail.
     * Version: 1.0.0
     */
    public static function render_leads() {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access LeadPilot leads.', 'tradepilot-ai'));
        }

        $lead_id = isset($_GET['lead_id']) ? absint($_GET['lead_id']) : 0;

        if ($lead_id) {
            self::render_detail($lead_id);
            return;
        }

        self::render_list();
    }

    /**
     * LeadPilot
     * Module: Admin
     * Function: Render recent lead list.
     * Version: 1.0.0
     */
    private static function render_list() {
        $leads = LeadPilot_Leads::recent(100);
        ?>
        <div class="wrap tradepilot-admin">
            <h1>LeadPilot Leads</h1>
            <p class="tradepilot-lede">Recent website enquiries captured by the smart lead funnel.</p>

            <table class="widefat striped tradepilot-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Postcode</th>
                        <th>Budget</th>
                        <th>Urgency</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($leads)) : ?>
                        <tr><td colspan="8">No leads captured yet. Add the shortcode <code>[tradepilot_lead_form]</code> to a page to start collecting enquiries.</td></tr>
                    <?php else : ?>
                        <?php foreach ($leads as $lead) : ?>
                            <tr>
                                <td><a href="<?php echo esc_url(self::detail_url($lead['id'])); ?>">#<?php echo esc_html($lead['id']); ?></a></td>
                                <td><?php echo esc_html($lead['created_at']); ?></td>
                                <td><?php echo esc_html($lead['customer_name']); ?></td>
                                <td><?php echo esc_html($lead['service_type']); ?></td>
                                <td><?php echo esc_html($lead['postcode']); ?></td>
                                <td><?php echo esc_html($lead['budget_range']); ?></td>
                                <td><?php echo esc_html($lead['urgency']); ?></td>
                                <td><?php echo esc_html($lead['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * LeadPilot
     * Module: Admin
     * Function: Render a single lead detail screen.
     * Version: 1.0.0
     */
    private static function render_detail($lead_id) {
        $lead = LeadPilot_Leads::get($lead_id);
        ?>
        <div class="wrap tradepilot-admin">
            <h1>Lead #<?php echo esc_html($lead_id); ?></h1>
            <p><a href="<?php echo esc_url(admin_url('admin.php?page=tradepilot-ai-leads')); ?>">← Back to leads</a></p>

            <?php if (!$lead) : ?>
                <div class="notice notice-error"><p>Lead not found.</p></div>
            <?php else : ?>
                <div class="tradepilot-card-grid">
                    <div class="tradepilot-card">
                        <h2>Customer</h2>
                        <p><strong>Name:</strong> <?php echo esc_html($lead['customer_name']); ?></p>
                        <p><strong>Phone:</strong> <?php echo esc_html($lead['customer_phone']); ?></p>
                        <p><strong>Email:</strong> <?php echo esc_html($lead['customer_email']); ?></p>
                        <p><strong>Postcode:</strong> <?php echo esc_html($lead['postcode']); ?></p>
                    </div>

                    <div class="tradepilot-card">
                        <h2>Job</h2>
                        <p><strong>Service:</strong> <?php echo esc_html($lead['service_type']); ?></p>
                        <p><strong>Budget:</strong> <?php echo esc_html($lead['budget_range']); ?></p>
                        <p><strong>Urgency:</strong> <?php echo esc_html($lead['urgency']); ?></p>
                        <p><strong>Status:</strong> <?php echo esc_html($lead['status']); ?></p>
                    </div>
                </div>

                <div class="tradepilot-panel">
                    <h2>Description</h2>
                    <p><?php echo nl2br(esc_html($lead['description'])); ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * LeadPilot
     * Module: Admin
     * Function: Build a safe lead detail admin URL.
     * Version: 1.0.0
     */
    private static function detail_url($lead_id) {
        return add_query_arg(
            array(
                'page'    => 'tradepilot-ai-leads',
                'lead_id' => absint($lead_id),
            ),
            admin_url('admin.php')
        );
    }
}

<?php
/**
 * TradePilot AI
 * Module: LeadPilot Admin
 * Function: Adds LeadPilot admin lead list, detail, photo preview and action screens.
 * Version: 1.3.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class LeadPilot_Admin {

    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'register_menu'), 20);
    }

    public static function register_menu() {
        add_submenu_page('tradepilot-ai', 'TradePilot AI - Leads', 'Leads', 'manage_options', 'tradepilot-ai-leads', array(__CLASS__, 'render_leads'));
    }

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

    private static function render_list() {
        $leads = LeadPilot_Leads::recent(100);
        ?>
        <div class="wrap tradepilot-admin">
            <h1>LeadPilot Leads</h1>
            <p class="tradepilot-lede">Recent website enquiries captured by the smart lead funnel.</p>
            <table class="widefat striped tradepilot-table">
                <thead><tr><th>ID</th><th>Date</th><th>Customer</th><th>Service</th><th>Postcode</th><th>Budget</th><th>Urgency</th><th>Status</th></tr></thead>
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

    private static function render_detail($lead_id) {
        $lead = LeadPilot_Leads::get($lead_id);
        $meta = self::meta($lead);
        $uploads = isset($meta['uploads']) && is_array($meta['uploads']) ? $meta['uploads'] : array();
        ?>
        <div class="wrap tradepilot-admin">
            <h1>Lead #<?php echo esc_html($lead_id); ?></h1>
            <p><a href="<?php echo esc_url(admin_url('admin.php?page=tradepilot-ai-leads')); ?>">Back to leads</a></p>
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
                <div class="tradepilot-panel"><h2>Description</h2><p><?php echo nl2br(esc_html($lead['description'])); ?></p></div>
                <div class="tradepilot-panel">
                    <h2>Uploaded Photos</h2>
                    <?php if (empty($uploads)) : ?>
                        <p>No photos were uploaded with this enquiry.</p>
                    <?php else : ?>
                        <div class="leadpilot-photo-grid">
                            <?php foreach ($uploads as $upload) : ?>
                                <?php if (!empty($upload['url'])) : ?>
                                    <a href="<?php echo esc_url($upload['url']); ?>" target="_blank" rel="noopener"><img src="<?php echo esc_url($upload['url']); ?>" alt="Lead photo" /></a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="tradepilot-panel">
                    <h2>Lead Action</h2>
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                        <input type="hidden" name="action" value="leadpilot_save_lead_action" />
                        <input type="hidden" name="lead_id" value="<?php echo esc_attr($lead_id); ?>" />
                        <?php wp_nonce_field('leadpilot_save_lead_action', 'leadpilot_action_nonce'); ?>
                        <label>Status
                            <select name="lead_status">
                                <?php foreach (array('new','contacted','quoted','booked','lost','archived') as $status) : ?>
                                    <option value="<?php echo esc_attr($status); ?>" <?php selected($lead['status'], $status); ?>><?php echo esc_html(ucfirst($status)); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label>Internal note<textarea name="admin_note" rows="4" class="large-text"></textarea></label>
                        <?php submit_button('Save Lead Action'); ?>
                    </form>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    private static function meta($lead) {
        if (!$lead || empty($lead['meta'])) {
            return array();
        }
        $meta = json_decode($lead['meta'], true);
        return is_array($meta) ? $meta : array();
    }

    private static function detail_url($lead_id) {
        return add_query_arg(array('page' => 'tradepilot-ai-leads', 'lead_id' => absint($lead_id)), admin_url('admin.php'));
    }
}

<?php
/**
 * TradePilot AI
 * Module: LeadPilot Admin
 * Function: Adds filtered lead list, AI detail panels, photo preview and action screens.
 * Version: 1.5.0
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
        $filters = array(
            'status' => isset($_GET['lead_status']) ? sanitize_key(wp_unslash($_GET['lead_status'])) : '',
            'service_type' => isset($_GET['service_type']) ? sanitize_text_field(wp_unslash($_GET['service_type'])) : '',
            'urgency' => isset($_GET['urgency']) ? sanitize_text_field(wp_unslash($_GET['urgency'])) : '',
        );
        $leads = LeadPilot_Leads::query($filters, 100);
        ?>
        <div class="wrap tradepilot-admin">
            <h1>LeadPilot Leads</h1>
            <p class="tradepilot-lede">Filter and review website enquiries captured by the smart lead funnel.</p>
            <form method="get" class="leadpilot-filter-bar">
                <input type="hidden" name="page" value="tradepilot-ai-leads" />
                <select name="lead_status"><option value="">All statuses</option><?php foreach (array('new','contacted','quoted','booked','lost','archived') as $status) : ?><option value="<?php echo esc_attr($status); ?>" <?php selected($filters['status'], $status); ?>><?php echo esc_html(ucfirst($status)); ?></option><?php endforeach; ?></select>
                <select name="service_type"><option value="">All services</option><?php foreach (array('Plumbing','Gas / Boiler','Electrical','Bathroom','Kitchen','Roofing','Plastering','Landscaping','Handyman','Other') as $service) : ?><option value="<?php echo esc_attr($service); ?>" <?php selected($filters['service_type'], $service); ?>><?php echo esc_html($service); ?></option><?php endforeach; ?></select>
                <select name="urgency"><option value="">All urgency</option><?php foreach (array('Flexible','This week','Within 48 hours','Emergency') as $urgency) : ?><option value="<?php echo esc_attr($urgency); ?>" <?php selected($filters['urgency'], $urgency); ?>><?php echo esc_html($urgency); ?></option><?php endforeach; ?></select>
                <button class="button button-primary" type="submit">Filter Leads</button><a class="button" href="<?php echo esc_url(admin_url('admin.php?page=tradepilot-ai-leads')); ?>">Reset</a>
            </form>
            <table class="widefat striped tradepilot-table">
                <thead><tr><th>ID</th><th>Date</th><th>Customer</th><th>Service</th><th>Postcode</th><th>Budget</th><th>Urgency</th><th>AI</th><th>Status</th></tr></thead>
                <tbody>
                    <?php if (empty($leads)) : ?>
                        <tr><td colspan="9">No leads match these filters.</td></tr>
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
                                <td><?php echo esc_html(!is_null($lead['score']) ? $lead['score'] . '/100 ' . $lead['temperature'] : 'Unscored'); ?></td>
                                <td><span class="leadpilot-status-badge"><?php echo esc_html($lead['status']); ?></span></td>
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
        $meta = $lead ? LeadPilot_Leads::meta($lead) : array();
        $uploads = isset($meta['uploads']) && is_array($meta['uploads']) ? $meta['uploads'] : array();
        $answers = isset($meta['dynamic_answers']) && is_array($meta['dynamic_answers']) ? $meta['dynamic_answers'] : array();
        $notes = isset($meta['admin_notes']) && is_array($meta['admin_notes']) ? $meta['admin_notes'] : array();
        $ai = isset($meta['ai']) && is_array($meta['ai']) ? $meta['ai'] : array();
        ?>
        <div class="wrap tradepilot-admin">
            <h1>Lead #<?php echo esc_html($lead_id); ?></h1>
            <p><a href="<?php echo esc_url(admin_url('admin.php?page=tradepilot-ai-leads')); ?>">Back to leads</a></p>
            <?php if (isset($_GET['tradepilot_status']) && 'saved' === sanitize_key(wp_unslash($_GET['tradepilot_status']))) : ?><div class="notice notice-success is-dismissible"><p>Lead action saved.</p></div><?php endif; ?>
            <?php if (!$lead) : ?>
                <div class="notice notice-error"><p>Lead not found.</p></div>
            <?php else : ?>
                <div class="tradepilot-card-grid">
                    <div class="tradepilot-card"><h2>Customer</h2><p><strong>Name:</strong> <?php echo esc_html($lead['customer_name']); ?></p><p><strong>Phone:</strong> <?php echo esc_html($lead['customer_phone']); ?></p><p><strong>Email:</strong> <?php echo esc_html($lead['customer_email']); ?></p><p><strong>Postcode:</strong> <?php echo esc_html($lead['postcode']); ?></p></div>
                    <div class="tradepilot-card"><h2>Job</h2><p><strong>Service:</strong> <?php echo esc_html($lead['service_type']); ?></p><p><strong>Budget:</strong> <?php echo esc_html($lead['budget_range']); ?></p><p><strong>Urgency:</strong> <?php echo esc_html($lead['urgency']); ?></p><p><strong>Status:</strong> <?php echo esc_html($lead['status']); ?></p></div>
                    <div class="tradepilot-card"><h2>AI Score</h2><strong><?php echo esc_html(!is_null($lead['score']) ? $lead['score'] . '/100' : '—'); ?></strong><p><?php echo esc_html(!empty($lead['temperature']) ? ucfirst($lead['temperature']) : 'Unscored'); ?></p></div>
                </div>
                <div class="tradepilot-panel"><h2>AI Summary</h2><?php self::render_ai($ai); ?></div>
                <div class="tradepilot-panel"><h2>Description</h2><p><?php echo nl2br(esc_html($lead['description'])); ?></p></div>
                <div class="tradepilot-panel"><h2>Extra Answers</h2><?php self::render_key_values($answers); ?></div>
                <div class="tradepilot-panel"><h2>Uploaded Photos</h2><?php self::render_photos($uploads); ?></div>
                <div class="tradepilot-panel"><h2>Admin Notes</h2><?php self::render_notes($notes); ?></div>
                <div class="tradepilot-panel"><h2>Lead Action</h2><?php self::render_action_form($lead_id, $lead['status']); ?></div>
            <?php endif; ?>
        </div>
        <?php
    }

    private static function render_ai($ai) {
        if (empty($ai)) { echo '<p>This lead has not been scored yet.</p>'; return; }
        echo '<p><strong>Summary:</strong> ' . esc_html($ai['summary']) . '</p>';
        echo '<p><strong>Next action:</strong> ' . esc_html($ai['next_action']) . '</p>';
        echo '<p><strong>Engine:</strong> ' . esc_html($ai['engine']) . ' at ' . esc_html($ai['generated_at']) . '</p>';
    }

    private static function render_key_values($items) {
        if (empty($items)) { echo '<p>No extra answers captured.</p>'; return; }
        echo '<table class="widefat striped"><tbody>';
        foreach ($items as $key => $value) { echo '<tr><th>' . esc_html(ucwords(str_replace('_', ' ', $key))) . '</th><td>' . esc_html($value) . '</td></tr>'; }
        echo '</tbody></table>';
    }

    private static function render_photos($uploads) {
        if (empty($uploads)) { echo '<p>No photos were uploaded with this enquiry.</p>'; return; }
        echo '<div class="leadpilot-photo-grid">';
        foreach ($uploads as $upload) { if (!empty($upload['url'])) { echo '<a href="' . esc_url($upload['url']) . '" target="_blank" rel="noopener"><img src="' . esc_url($upload['url']) . '" alt="Lead photo" /></a>'; } }
        echo '</div>';
    }

    private static function render_notes($notes) {
        if (empty($notes)) { echo '<p>No internal notes yet.</p>'; return; }
        echo '<ul class="leadpilot-notes">';
        foreach (array_reverse($notes) as $note) { echo '<li><strong>' . esc_html($note['created_at']) . '</strong><br />' . esc_html($note['note']) . '</li>'; }
        echo '</ul>';
    }

    private static function render_action_form($lead_id, $current_status) {
        ?>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="leadpilot_save_lead_action" /><input type="hidden" name="lead_id" value="<?php echo esc_attr($lead_id); ?>" /><?php wp_nonce_field('leadpilot_save_lead_action', 'leadpilot_action_nonce'); ?>
            <label>Status<select name="lead_status"><?php foreach (array('new','contacted','quoted','booked','lost','archived') as $status) : ?><option value="<?php echo esc_attr($status); ?>" <?php selected($current_status, $status); ?>><?php echo esc_html(ucfirst($status)); ?></option><?php endforeach; ?></select></label>
            <label>Internal note<textarea name="admin_note" rows="4" class="large-text"></textarea></label><?php submit_button('Save Lead Action'); ?>
        </form>
        <?php
    }

    private static function detail_url($lead_id) {
        return add_query_arg(array('page' => 'tradepilot-ai-leads', 'lead_id' => absint($lead_id)), admin_url('admin.php'));
    }
}

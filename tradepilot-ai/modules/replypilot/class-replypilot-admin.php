<?php
/**
 * TradePilot AI
 * Module: ReplyPilot Admin
 * Function: Renders template settings and lead message controls.
 * Version: 1.1.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class ReplyPilot_Admin {

    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'register_menu'), 30);
    }

    public static function register_menu() {
        add_submenu_page('tradepilot-ai', 'TradePilot AI - ReplyPilot', 'ReplyPilot', 'manage_options', 'tradepilot-ai-replypilot', array(__CLASS__, 'render_templates_page'));
    }

    public static function render_templates_page() {
        if (!current_user_can('manage_options')) { wp_die(esc_html__('You do not have permission to access ReplyPilot.', 'tradepilot-ai')); }
        $templates = ReplyPilot_Templates::get_all();
        ?>
        <div class="wrap tradepilot-admin">
            <h1>ReplyPilot Templates</h1>
            <?php if (isset($_GET['tradepilot_status']) && 'saved' === sanitize_key(wp_unslash($_GET['tradepilot_status']))) : ?><div class="notice notice-success is-dismissible"><p>ReplyPilot templates saved.</p></div><?php endif; ?>
            <p class="tradepilot-lede">Edit the core customer message templates. Available tags: <code><?php echo esc_html(implode('</code> <code>', ReplyPilot_Templates::available_tags())); ?></code></p>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="replypilot_save_templates" />
                <?php wp_nonce_field('replypilot_save_templates', 'replypilot_templates_nonce'); ?>
                <?php foreach ($templates as $key => $template) : ?>
                    <div class="tradepilot-panel">
                        <h2><?php echo esc_html($template['label']); ?></h2>
                        <label>Subject<input type="text" class="large-text" name="templates[<?php echo esc_attr($key); ?>][subject]" value="<?php echo esc_attr($template['subject']); ?>" /></label>
                        <label>Body<textarea class="large-text" rows="7" name="templates[<?php echo esc_attr($key); ?>][body]"><?php echo esc_textarea($template['body']); ?></textarea></label>
                    </div>
                <?php endforeach; ?>
                <?php submit_button('Save ReplyPilot Templates'); ?>
            </form>
        </div>
        <?php
    }

    public static function render_lead_panel($lead) {
        if (!$lead || empty($lead['id'])) { return; }
        $templates = ReplyPilot_Templates::get_all();
        $meta = LeadPilot_Leads::meta($lead);
        $messages = isset($meta['replypilot_messages']) && is_array($meta['replypilot_messages']) ? $meta['replypilot_messages'] : array();
        $queue = class_exists('ReplyPilot_Scheduler') ? ReplyPilot_Scheduler::queue($lead) : array();
        ?>
        <div class="tradepilot-panel">
            <h2>ReplyPilot Messages</h2>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="replypilot_send_template" />
                <input type="hidden" name="lead_id" value="<?php echo esc_attr($lead['id']); ?>" />
                <?php wp_nonce_field('replypilot_send_template', 'replypilot_template_nonce'); ?>
                <label>Template<select name="template_key"><?php foreach ($templates as $key => $template) : ?><option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($template['label']); ?></option><?php endforeach; ?></select></label>
                <?php submit_button('Send Template'); ?>
            </form>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="replypilot-schedule-form">
                <input type="hidden" name="action" value="replypilot_schedule_template" />
                <input type="hidden" name="lead_id" value="<?php echo esc_attr($lead['id']); ?>" />
                <?php wp_nonce_field('replypilot_schedule_template', 'replypilot_schedule_nonce'); ?>
                <label>Schedule template<select name="template_key"><?php foreach ($templates as $key => $template) : ?><option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($template['label']); ?></option><?php endforeach; ?></select></label>
                <label>Days after today<input type="number" min="1" max="30" name="days_after" value="2" /></label>
                <?php submit_button('Schedule Follow-Up'); ?>
            </form>
            <h3>Scheduled Follow-Ups</h3>
            <?php self::render_queue($queue); ?>
            <h3>Message History</h3>
            <?php self::render_history($messages); ?>
        </div>
        <?php
    }

    private static function render_queue($queue) {
        if (empty($queue)) { echo '<p>No follow-ups scheduled yet.</p>'; return; }
        echo '<ul class="leadpilot-notes">';
        foreach (array_reverse($queue) as $item) {
            echo '<li><strong>' . esc_html($item['scheduled_for']) . '</strong><br />' . esc_html($item['template']) . ' — ' . esc_html($item['status']) . '</li>';
        }
        echo '</ul>';
    }

    private static function render_history($messages) {
        if (empty($messages)) { echo '<p>No ReplyPilot messages recorded yet.</p>'; return; }
        echo '<ul class="leadpilot-notes">';
        foreach (array_reverse($messages) as $message) {
            echo '<li><strong>' . esc_html($message['created_at']) . '</strong><br />' . esc_html($message['template']) . ' — ' . esc_html(!empty($message['sent']) ? 'sent' : 'not sent') . '<br />' . esc_html($message['subject']) . '</li>';
        }
        echo '</ul>';
    }
}

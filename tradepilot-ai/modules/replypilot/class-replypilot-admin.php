<?php
/**
 * TradePilot AI
 * Module: ReplyPilot Admin
 * Function: Renders manual template sending controls for lead detail screens.
 * Version: 1.0.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class ReplyPilot_Admin {

    public static function render_lead_panel($lead) {
        if (!$lead || empty($lead['id'])) {
            return;
        }

        $templates = ReplyPilot_Templates::get_all();
        $meta = LeadPilot_Leads::meta($lead);
        $messages = isset($meta['replypilot_messages']) && is_array($meta['replypilot_messages']) ? $meta['replypilot_messages'] : array();
        ?>
        <div class="tradepilot-panel">
            <h2>ReplyPilot Messages</h2>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="replypilot_send_template" />
                <input type="hidden" name="lead_id" value="<?php echo esc_attr($lead['id']); ?>" />
                <?php wp_nonce_field('replypilot_send_template', 'replypilot_template_nonce'); ?>
                <label>Template
                    <select name="template_key">
                        <?php foreach ($templates as $key => $template) : ?>
                            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($template['label']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <?php submit_button('Send Template'); ?>
            </form>
            <?php self::render_history($messages); ?>
        </div>
        <?php
    }

    private static function render_history($messages) {
        if (empty($messages)) {
            echo '<p>No ReplyPilot messages recorded yet.</p>';
            return;
        }

        echo '<ul class="leadpilot-notes">';
        foreach (array_reverse($messages) as $message) {
            echo '<li><strong>' . esc_html($message['created_at']) . '</strong><br />';
            echo esc_html($message['template']) . ' — ' . esc_html(!empty($message['sent']) ? 'sent' : 'not sent') . '<br />';
            echo esc_html($message['subject']) . '</li>';
        }
        echo '</ul>';
    }
}

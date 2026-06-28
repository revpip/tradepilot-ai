<?php
/**
 * TradePilot AI
 * Module: LeadPilot
 * Function: Public lead funnel, shortcode and form submission handler.
 * Version: 1.2.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class LeadPilot {

    public static function init() {
        add_shortcode('tradepilot_lead_form', array(__CLASS__, 'render_shortcode'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
        add_action('admin_post_tradepilot_submit_lead', array(__CLASS__, 'handle_submit'));
        add_action('admin_post_nopriv_tradepilot_submit_lead', array(__CLASS__, 'handle_submit'));
    }

    public static function enqueue_assets() {
        wp_enqueue_style('leadpilot-public', TRADEPILOT_AI_URL . 'modules/leadpilot/public.css', array(), TRADEPILOT_AI_VERSION);
    }

    public static function render_shortcode($atts = array()) {
        $settings = TradePilot_Settings::get_all();
        $action = admin_url('admin-post.php');
        ob_start();
        ?>
        <div class="leadpilot-form-wrap">
            <div class="leadpilot-header">
                <p class="leadpilot-kicker">TradePilot AI</p>
                <h2>Tell us what you need doing</h2>
                <p>Share the essentials and we will route your enquiry to the right team.</p>
            </div>

            <?php if (isset($_GET['leadpilot_status']) && 'received' === sanitize_key(wp_unslash($_GET['leadpilot_status']))) : ?>
                <div class="leadpilot-success">Thank you — your enquiry has been received. We will review it and come back to you shortly.</div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" action="<?php echo esc_url($action); ?>" class="leadpilot-form">
                <input type="hidden" name="action" value="tradepilot_submit_lead" />
                <input type="hidden" name="source" value="website" />
                <?php wp_nonce_field('tradepilot_submit_lead', 'leadpilot_nonce'); ?>

                <label>What kind of work do you need?
                    <select name="service_type" required>
                        <option value="">Choose a service</option>
                        <option value="Plumbing">Plumbing</option>
                        <option value="Gas / Boiler">Gas / Boiler</option>
                        <option value="Electrical">Electrical</option>
                        <option value="Bathroom">Bathroom</option>
                        <option value="Kitchen">Kitchen</option>
                        <option value="Roofing">Roofing</option>
                        <option value="Plastering">Plastering</option>
                        <option value="Landscaping">Landscaping</option>
                        <option value="Handyman">Handyman</option>
                        <option value="Other">Other</option>
                    </select>
                </label>

                <div class="leadpilot-grid">
                    <label>Property type
                        <select name="property_type">
                            <option value="House">House</option>
                            <option value="Flat">Flat</option>
                            <option value="Commercial">Commercial</option>
                            <option value="Rental property">Rental property</option>
                        </select>
                    </label>
                    <label>Type of enquiry
                        <select name="issue_type">
                            <option value="Repair">Repair</option>
                            <option value="Installation">Installation</option>
                            <option value="Renovation">Renovation</option>
                            <option value="Emergency">Emergency</option>
                            <option value="Quote only">Quote only</option>
                        </select>
                    </label>
                </div>

                <label>Describe the job
                    <textarea name="description" rows="5" required placeholder="Tell us what has happened, what you need, and anything useful about access, timing or photos."></textarea>
                </label>

                <div class="leadpilot-grid">
                    <label>Postcode
                        <input type="text" name="postcode" required placeholder="FY1 1AA" />
                    </label>
                    <label>Budget range
                        <select name="budget_range">
                            <option value="Not sure">Not sure yet</option>
                            <option value="Under £250">Under £250</option>
                            <option value="£250 - £1,000">£250 - £1,000</option>
                            <option value="£1,000 - £5,000">£1,000 - £5,000</option>
                            <option value="£5,000 - £15,000">£5,000 - £15,000</option>
                            <option value="£15,000+">£15,000+</option>
                        </select>
                    </label>
                </div>

                <div class="leadpilot-grid">
                    <label>How urgent is it?
                        <select name="urgency">
                            <option value="Flexible">Flexible</option>
                            <option value="This week">This week</option>
                            <option value="Within 48 hours">Within 48 hours</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </label>
                    <label>Best time to contact you
                        <select name="preferred_contact_time">
                            <option value="Any time">Any time</option>
                            <option value="Morning">Morning</option>
                            <option value="Afternoon">Afternoon</option>
                            <option value="Evening">Evening</option>
                        </select>
                    </label>
                </div>

                <label>Upload helpful photos
                    <input type="file" name="lead_photos[]" multiple accept="image/jpeg,image/png,image/webp" />
                </label>

                <div class="leadpilot-grid">
                    <label>Your name
                        <input type="text" name="customer_name" required />
                    </label>
                    <label>Phone number
                        <input type="tel" name="customer_phone" required />
                    </label>
                </div>

                <label>Email address
                    <input type="email" name="customer_email" />
                </label>

                <label class="leadpilot-consent">
                    <input type="checkbox" name="consent" value="1" required />
                    I agree to be contacted about this enquiry.
                </label>

                <button type="submit">Send Enquiry</button>

                <?php if (!empty($settings['business_name'])) : ?>
                    <p class="leadpilot-footnote">Powered by TradePilot AI for <?php echo esc_html($settings['business_name']); ?>.</p>
                <?php endif; ?>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    public static function handle_submit() {
        $nonce = isset($_POST['leadpilot_nonce']) ? sanitize_text_field(wp_unslash($_POST['leadpilot_nonce'])) : '';

        if (!wp_verify_nonce($nonce, 'tradepilot_submit_lead')) {
            wp_die(esc_html__('Security check failed. Please reload and try again.', 'tradepilot-ai'));
        }

        if (empty($_POST['consent'])) {
            wp_die(esc_html__('Please confirm consent so we can contact you about this enquiry.', 'tradepilot-ai'));
        }

        $uploads = LeadPilot_Upload_Helper::collect('lead_photos');
        $_POST['meta'] = array('uploads' => $uploads);

        $lead_id = LeadPilot_Leads::create($_POST);

        if (!$lead_id) {
            wp_die(esc_html__('Sorry, the enquiry could not be saved. Please try again.', 'tradepilot-ai'));
        }

        TradePilot_Audit_Log::record('lead_created', 'New LeadPilot enquiry received.', array('lead_id' => $lead_id, 'uploads' => count($uploads)));
        LeadPilot_Notifications::send_new_lead_notifications($lead_id);

        $redirect = wp_get_referer() ? wp_get_referer() : home_url('/');
        wp_safe_redirect(add_query_arg('leadpilot_status', 'received', $redirect));
        exit;
    }
}

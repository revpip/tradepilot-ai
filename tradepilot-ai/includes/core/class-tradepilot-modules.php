<?php
/**
 * TradePilot Core
 * Module: Module Registry
 * Function: Registers and loads TradePilot intelligence modules.
 * Version: 1.2.0
 *
 * @package TradePilotAI
 */

if (!defined('ABSPATH')) {
    exit;
}

class TradePilot_Modules {

    public static function registry() {
        return array(
            'leadpilot' => array(
                'name'        => 'LeadPilot',
                'description' => 'Smart lead capture, qualification and scoring.',
                'version'     => '1.2.0',
                'file'        => 'modules/leadpilot/leadpilot.php',
                'enabled'     => true,
            ),
            'quotepilot' => array(
                'name'        => 'QuotePilot',
                'description' => 'Estimate, quote and conversion workflows.',
                'version'     => '0.1.0',
                'file'        => 'modules/quotepilot/quotepilot.php',
                'enabled'     => false,
            ),
            'routepilot' => array(
                'name'        => 'RoutePilot',
                'description' => 'Contractor and territory routing intelligence.',
                'version'     => '0.1.0',
                'file'        => 'modules/routepilot/routepilot.php',
                'enabled'     => false,
            ),
            'replypilot' => array(
                'name'        => 'ReplyPilot',
                'description' => 'Automated customer messaging and follow-ups.',
                'version'     => '0.1.0',
                'file'        => 'modules/replypilot/replypilot.php',
                'enabled'     => false,
            ),
            'reviewpilot' => array(
                'name'        => 'ReviewPilot',
                'description' => 'Review requests and reputation workflows.',
                'version'     => '0.1.0',
                'file'        => 'modules/reviewpilot/reviewpilot.php',
                'enabled'     => false,
            ),
            'insightpilot' => array(
                'name'        => 'InsightPilot',
                'description' => 'Analytics, reporting and performance intelligence.',
                'version'     => '0.1.0',
                'file'        => 'modules/insightpilot/insightpilot.php',
                'enabled'     => false,
            ),
            'franchisepilot' => array(
                'name'        => 'FranchisePilot',
                'description' => 'Territories, franchise dashboards and licensing controls.',
                'version'     => '0.1.0',
                'file'        => 'modules/franchisepilot/franchisepilot.php',
                'enabled'     => false,
            ),
        );
    }

    public static function load() {
        $modules = self::get_modules();

        foreach ($modules as $key => $module) {
            if (empty($module['enabled']) || empty($module['file'])) {
                continue;
            }

            $path = TRADEPILOT_AI_PATH . $module['file'];

            if (file_exists($path)) {
                require_once $path;
            }
        }
    }

    public static function get_modules() {
        $registry = self::registry();
        $stored   = get_option('tradepilot_ai_modules', array());

        foreach ($registry as $key => $module) {
            if (isset($stored[$key]['enabled'])) {
                $registry[$key]['enabled'] = (bool) $stored[$key]['enabled'];
            }
        }

        return $registry;
    }

    public static function set_module_state($module_key, $enabled) {
        $modules = self::get_modules();

        if (!isset($modules[$module_key])) {
            return false;
        }

        $modules[$module_key]['enabled'] = (bool) $enabled;
        update_option('tradepilot_ai_modules', $modules, false);

        return true;
    }
}

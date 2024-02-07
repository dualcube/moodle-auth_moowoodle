<?php
/**
 *
 * @package    auth_moowoodle_moodle_connector
 * @author     DualCube <admin@dualcube.com>
 * @copyright  2023 DualCube Team(https://dualcube.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
	$settings->add(new admin_setting_configtext('auth_moowoodle_moodle_connector/encryptkey', get_string('key', 'auth_moowoodle_moodle_connector'), get_string('message', 'auth_moowoodle_moodle_connector', 'auth'), '', PARAM_RAW));
	$settings->add(new admin_setting_configtext('auth_moowoodle_moodle_connector/wpsiteurl', get_string('wpsiteurl', 'auth_moowoodle_moodle_connector'), get_string('message2', 'auth_moowoodle_moodle_connector', 'auth'), '', PARAM_RAW));
	$settings->add(new admin_setting_configtext('auth_moowoodle_moodle_connector/timelimit', get_string('timelimit', 'auth_moowoodle_moodle_connector'), get_string('message3', 'auth_moowoodle_moodle_connector', 'auth'), '5', PARAM_INT));
}

?>

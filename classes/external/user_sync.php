<?php
/**
 *
 * @package    auth_moowoodle_moodle_connector
 * @author     DualCube <admin@dualcube.com>
 * @copyright  2023 DualCube Team(https://dualcube.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace auth_moowoodle_moodle_connector\external;

defined('MOODLE_INTERNAL') || die();

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_value;
class user_sync extends external_api {
	public static function execute_parameters(): external_function_parameters {
		return new external_function_parameters(
			array(
				'end_id' => new external_value(PARAM_RAW, 'The Last id to send next batch of user data'),
				'limit' => new external_value(PARAM_RAW, 'The limit to sent batch of user data'),
			)
		);
	}

	public static function execute($end_id, $limit) {
		global $DB, $CFG;
		if(is_numeric($limit) && is_numeric($end_id)) {
			$limit = (int)$limit+1;
			$sql = "SELECT u.id, u.email, u.username, u.password, u.firstname, u.lastname FROM {user} u WHERE u.id > ".(int)$end_id." AND u.deleted = 0 ORDER BY u.id ASC LIMIT ".$limit;
			$users = $DB->get_records_sql($sql);
			$response = array(
				'status' => 'success',
				'data' => json_encode($users),
			);
			return ($response);
		} elseif (is_array(json_decode($limit, true)) && is_array(json_decode($end_id, true))) {
			require_once($CFG->dirroot . '/user/lib.php');
			$wp_user_data = json_decode($limit, true);
			$sync_settings = json_decode($end_id, true);
			$moodle_user_data = $DB->get_record('user', array('email'=> $wp_user_data['email']));
			$moodle_user_id['created'] = false;
			if($moodle_user_data){
				$user_id = $moodle_user_data->id;
				$moodle_user_data->email =$wp_user_data['email'];
				if (isset($sync_settings['sync_username']) && $sync_settings['sync_username'] == "Enable") {
					$moodle_user_data->username = $wp_user_data['username'];
				}
				if (( $wp_user_data['password'] != null && isset($sync_settings['sync_password']) && $sync_settings['sync_password'] == "Enable")) {
					if(strpos( $wp_user_data['password'], "$2y$") === 0){
						$moodle_user_data->password =  $wp_user_data['password'];
					} else {
						$moodle_user_id['created'] = true;
					}
				}
				if (isset($sync_settings['sync_user_first_name']) && $sync_settings['sync_user_first_name'] == "Enable" && $wp_user_data['firstname'] != null) {
					$moodle_user_data->firstname = $wp_user_data['firstname'];
				}
				if (isset($sync_settings['sync_user_last_name']) && $sync_settings['sync_user_last_name'] == "Enable" && $wp_user_data['lastname'] != null) {
					$moodle_user_data->lastname = $wp_user_data['lastname'];
				}
				user_update_user($moodle_user_data, true, false);
			} else {
				$moodle_user_data = new stdClass();
				$moodle_user_data->email =$wp_user_data['email'];
				$moodle_user_data->username = $wp_user_data['username'];
				$moodle_user_data->password = $wp_user_data['password'];
				if(strpos( $wp_user_data['password'], "$2y$") !== 0)
				$moodle_user_id['created'] = true;
				$moodle_user_data->firstname = $wp_user_data['firstname'];
				$moodle_user_data->lastname = $wp_user_data['lastname'];
				$moodle_user_data->auth = 'manual';
				$moodle_user_data->lang = $wp_user_data['lang'];
				$user_id = user_create_user($moodle_user_data, true, false);
			}
			$moodle_user_id['id'] = $user_id;
			$response = array(
				'status' => 'success',
				'data' => json_encode($moodle_user_id),
			);
			return ($response);
		} else {
			$response = array(
				'status' => 'failed',
				'data' => json_encode('Bad Request'),
			);
			return ($response);
		}
	}
	public static function execute_returns(): external_single_structure {
		return new external_single_structure(
			array(
				'status' => new external_value(PARAM_RAW, 'status: success if success'),
				'data' => new external_value(PARAM_RAW, 'users: all user data'),
			)
		);
	}
}

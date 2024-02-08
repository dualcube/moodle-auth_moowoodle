<?php
// This file is part of Moodle - http://moodle.org/
/**
 * Privacy Subsystem implementation for auth_email.
 *
 * @package    auth_moowoodle_moodle_connector
 * @author     DualCube <admin@dualcube.com>
 * @copyright  2023 DualCube Team(https://dualcube.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace auth_moowoodle_moodle_connector\privacy;
defined('MOODLE_INTERNAL') || die();

class provider implements \core_privacy\local\metadata\null_provider {
    public static function get_reason(): string {
        return 'privacy:metadata';
    }
}

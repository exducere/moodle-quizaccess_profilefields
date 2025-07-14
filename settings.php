<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Settings for the quizaccess_profilefields plugin.
 *
 * @package    quizaccess_profilefields
 * @copyright  2025 Casen Xu <casenxu@exducereonline.com>
 * @copyright  Exducere Online <@link https://exducereonline.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use quizaccess_profilefields\admin_config_setting_profilefields_list_editor;
use quizaccess_profilefields\admin_config_setting_profilefields_list;

if ($ADMIN->fulltree && $hassiteconfig) {
    global $DB;

    $settings->add(new admin_config_setting_profilefields_list_editor());

    $settings->add(new admin_setting_heading('quizaccess_profilefields/heading',
            get_string('generalsettings', 'admin'), get_string('configintro', 'quiz')));

    // Enable/disable plugin globally.
    $settings->add(new admin_setting_configcheckbox(
        'quizaccess_profilefields/enable_quizaccess_profilefields',
        get_string('enabled', 'quizaccess_profilefields'),
        get_string('enabled_desc', 'quizaccess_profilefields'),
        1
    ));

    // Default conditions to apply to new quizzes.
    $choices = $DB->get_records_menu('quizaccess_profile_condition', [], 'sortorder ASC, name ASC', 'id, name');
    if (!empty($choices)) {
        $defaultsetting = ['value' => [], 'adv' => true];
        $settings->add(new admin_config_setting_profilefields_list('quizaccess_profilefields/defaultconditions',
                get_string('defaultconditions', 'quizaccess_profilefields'),
                get_string('defaultconditions_desc', 'quizaccess_profilefields'),
                $defaultsetting, $choices));
    }
}


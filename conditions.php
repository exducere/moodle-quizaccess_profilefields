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
 * Form for editing profile fields conditions.
 *
 * @package    quizaccess_profilefields
 * @copyright  2025 Casen Xu <casenxu@exducereonline.com>
 * @copyright  Exducere Online <@link https://exducereonline.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../../config.php');

require_login();
require_capability('moodle/site:config', context_system::instance());
require_sesskey();

$action = required_param('action', PARAM_ALPHANUMEXT);
$id = required_param('id', PARAM_INT);

switch ($action) {
    case 'delete':
        $DB->delete_records('quizaccess_profile_fields', ['conditions' => $id]);
        $DB->delete_records('quizaccess_profile_condition', ['id' => $id]);
        quizaccess_profilefields_reorder_conditions();
        break;

    case 'up':
        $conditions = $DB->get_records('quizaccess_profile_condition', [], 'sortorder ASC, name ASC');
        $previous = null;
        foreach ($conditions as $condition) {
            if ($condition->id == $id) {
                if ($previous) {
                    $temp = $condition->sortorder;
                    $condition->sortorder = $previous->sortorder;
                    $previous->sortorder = $temp;
                    $DB->update_record('quizaccess_profile_condition', $condition);
                    $DB->update_record('quizaccess_profile_condition', $previous);
                    quizaccess_profilefields_reorder_conditions();
                    break;
                }
            }
            $previous = $condition;
        }
        break;

    case 'down':
        $conditions = $DB->get_records('quizaccess_profile_condition', [], 'sortorder DESC, name DESC');
        $previous = null;
        foreach ($conditions as $condition) {
            if ($condition->id == $id) {
                if ($previous) {
                    $temp = $condition->sortorder;
                    $condition->sortorder = $previous->sortorder;
                    $previous->sortorder = $temp;
                    $DB->update_record('quizaccess_profile_condition', $condition);
                    $DB->update_record('quizaccess_profile_condition', $previous);
                    quizaccess_profilefields_reorder_conditions();
                    break;
                }
            }
            $previous = $condition;
        }
        break;
}

redirect(new moodle_url('/admin/settings.php', ['section' => 'modsettingsquizcatprofilefields']));

/**
 * Corrige el orden de clasificación.
 */
function quizaccess_profilefields_reorder_conditions() {
    global $DB;
    $conditions = $DB->get_records('quizaccess_profile_condition', [], 'sortorder ASC, name ASC');
    $current = 1;
    foreach ($conditions as $condition) {
        if ($condition->sortorder != $current) {
            $condition->sortorder = $current;
            $DB->update_record('quizaccess_profile_condition', $condition);
        }
        $current++;
    }
}


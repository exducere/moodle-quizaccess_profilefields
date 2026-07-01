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
 * Custom admin config setting for editing profile fields conditions.
 *
 * @package    quizaccess_profilefields
 * @copyright  2025 Casen Xu <casenxu@exducereonline.com>
 * @copyright  Exducere Online <@link https://exducereonline.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../../../config.php");

require_login();
require_capability('moodle/site:config', context_system::instance());

$id = optional_param('id', 0, PARAM_INT);

$PAGE->set_pagelayout('admin');
$pageurl = new moodle_url('/admin/settings.php', ['section' => 'modsettingsquizcatprofilefields']);
$PAGE->set_url($pageurl);
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('editcondition', 'quizaccess_profilefields'));
$PAGE->set_heading($COURSE->fullname);

$formurl = new moodle_url('/mod/quiz/accessrule/profilefields/condition.php');
$mform = new \quizaccess_profilefields\form_edit_conditions($formurl);
if ($id) {
    $condition = $DB->get_record('quizaccess_profilefields_conditions', ['id' => $id]);
    $condition->message = json_decode($condition->message, true);
    $mform->set_data($condition);
}

if ($mform->is_cancelled()) {
    redirect($pageurl);
} else if ($data = $mform->get_data()) {
    require_sesskey();
    if ($id) {
        $condition->name = $data->name;
        $condition->fieldid = $data->fieldid;
        $condition->operator = $data->operator;
        $condition->value = ($data->operator === 'isempty' ||  $data->operator === 'isnotempty') ? '' : $data->value;
        $condition->missingfield = $data->missingfield;
        $condition->message = json_encode($data->message);
        $condition->timemodified = time();
        $DB->update_record('quizaccess_profilefields_conditions', $condition);
    } else {
        $condition = new stdClass();
        $condition->name = $data->name;
        $condition->fieldid = $data->fieldid;
        $condition->operator = $data->operator;
        $condition->value = ($data->operator === 'isempty' ||  $data->operator === 'isnotempty') ? '' : $data->value;
        $condition->missingfield = $data->missingfield;
        $condition->message = json_encode($data->message);
        $condition->sortorder = $DB->get_field_sql('SELECT MAX(sortorder) + 1 FROM {quizaccess_profilefields_conditions}');
        if (is_null($condition->sortorder)) {
            $condition->sortorder = 1;
        }
        $condition->timecreated = time();
        $condition->timemodified = time();
        $DB->insert_record('quizaccess_profilefields_conditions', $condition);
    }
    redirect($pageurl);
}
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();


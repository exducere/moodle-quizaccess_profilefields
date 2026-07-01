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
namespace quizaccess_profilefields;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/adminlib.php');

use admin_setting;
use moodle_url;
use pix_icon;
use html_table;
use html_writer;

/**
 * Class Custom admin config setting for editing profile fields conditions.
 *
 * @package    quizaccess_profilefields
 * @copyright  2025 Casen Xu <casenxu@exducereonline.com>
 * @copyright  Exducere Online <@link https://exducereonline.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_config_setting_profilefields_list_editor extends admin_setting {

    /**
     * Constructor
     * Initializes the setting with no save functionality and sets the title.
     */
    public function __construct() {
        $this->nosave = true;
        parent::__construct('quizaccess_profilefields_list_editor',
                get_string('manageconditions', 'quizaccess_profilefields'), '', '');
    }

    /**
     * Always returns true, does nothing.
     *
     * @return true
     */
    public function get_setting() {
        return true;
    }

    /**
     * Always returns true, does nothing.
     *
     * @return true
     */
    public function get_defaultsetting() {
        return true;
    }

    /**
     * Always returns empty string, does not save anything.
     *
     * @param mixed $data Ignored.
     * @return string Always returns empty string.
     */
    public function write_setting($data) {
        return '';
    }

    /**
     * Generate html with action links.
     *
     * @param \stdClass $condition The condition record.
     * @param int $current The current position.
     * @param int $count The total number of conditions.
     * @return string
     */
    private function actions_list($condition, $current, $count) {
        global $OUTPUT;

        $url = new moodle_url('/mod/quiz/accessrule/profilefields/conditions.php', ['sesskey' => sesskey()]);

        $actions = '';
        if ($current != 1 && $count > 1) {
            $upurl = new moodle_url($url, ['action' => 'up', 'id' => $condition->id]);
            $upicon = new pix_icon('t/up', get_string('up'));
            $actions .= $OUTPUT->action_link($upurl, '', null, [], $upicon);
        } else {
            $actions .= $OUTPUT->pix_icon('spacer', '');
        }

        if ($current != $count && $count > 1) {
            $downurl = new moodle_url($url, ['action' => 'down', 'id' => $condition->id]);
            $downicon = new pix_icon('t/down', get_string('down'));
            $actions .= $OUTPUT->action_link($downurl, '', null, [], $downicon);
        } else {
            $actions .= $OUTPUT->pix_icon('spacer', '');
        }

        $editurl = new moodle_url('/mod/quiz/accessrule/profilefields/condition.php', ['id' => $condition->id]);
        $editicon = new pix_icon('t/edit', get_string('edit'));
        $actions .= $OUTPUT->action_link($editurl, '', null, [], $editicon);

        $deleteurl = new moodle_url($url, ['action' => 'delete', 'id' => $condition->id]);
        $deleteicon = new pix_icon('t/delete', get_string('delete'));
        $actions .= $OUTPUT->action_link($deleteurl, '', null, [], $deleteicon);

        return $actions;
    }

    /**
     * Build the XHTML to display the control.
     *
     * @param string $data Not used.
     * @param string $query The search query.
     * @return string
     */
    public function output_html($data, $query = '') {
        global $OUTPUT, $DB;

        $table = new html_table();
        $table->head = [
            get_string('name'),
            get_string('profilefield', 'quizaccess_profilefields'),
            get_string('operator', 'quizaccess_profilefields'),
            get_string('value', 'quizaccess_profilefields'),
            '',
        ];
        $table->colclasses = ['leftalign', 'leftalign', 'leftalign', 'leftalign', 'centeralign'];
        $table->id = 'quizaccess_profilefields';
        $table->attributes['class'] = 'admintable generaltable';
        $table->data = [];

        $conditions = $DB->get_records('quizaccess_profilefields_conditions', [], 'sortorder ASC');
        $current = 1;
        $count = count($conditions);

        // Preload all user_info_field records in bulk to avoid N+1 queries in the loop.
        $fields = [];
        if (!empty($conditions)) {
            $fieldids = array_unique(array_column((array) $conditions, 'fieldid'));
            list($fieldinsql, $fieldparams) = $DB->get_in_or_equal($fieldids);
            $fields = $DB->get_records_select('user_info_field', 'id ' . $fieldinsql, $fieldparams);
        }

        $operators = [
            'contains' => get_string('operator_contains', 'quizaccess_profilefields'),
            'notcontains' => get_string('operator_notcontains', 'quizaccess_profilefields'),
            'equals' => get_string('operator_equals', 'quizaccess_profilefields'),
            'containsi' => get_string('operator_containsi', 'quizaccess_profilefields'),
            'notcontainsi' => get_string('operator_notcontainsi', 'quizaccess_profilefields'),
            'isempty' => get_string('operator_isempty', 'quizaccess_profilefields'),
            'isnotempty' => get_string('operator_isnotempty', 'quizaccess_profilefields'),
        ];

        foreach ($conditions as $condition) {
            $fieldname = isset($fields[$condition->fieldid]) ?
                $fields[$condition->fieldid]->name :
                get_string('unknownfield', 'quizaccess_profilefields');

            // Get the operator name.
            $operatorname = isset($operators[$condition->operator]) ?
                $operators[$condition->operator] : $condition->operator;

            $table->data[] = [
                $condition->name,
                $fieldname,
                $operatorname,
                $condition->value,
                $this->actions_list($condition, $current, $count),
            ];
            $current++;
        }

        $addurl = new moodle_url('/mod/quiz/accessrule/profilefields/condition.php');
        $addicon = new pix_icon('t/add', get_string('add'));
        $addlink = $OUTPUT->action_link($addurl, '', null, [], $addicon);
        $table->data[] = [$addlink, '', '', '', ''];

        $return = $OUTPUT->heading(get_string('manageconditions', 'quizaccess_profilefields'), 3);
        $return .= $OUTPUT->box_start('generalbox');
        $return .= html_writer::table($table);
        $return .= html_writer::div(get_string('tablenosave', 'admin'));
        $return .= $OUTPUT->box_end();
        return highlight($query, $return);
    }
}


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
 * Implementation of the quizaccess_profilefields plugin.
 *
 * @package    quizaccess_profilefields
 * @copyright  2025 Casen Xu <casenxu@exducereonline.com>
 * @copyright  Exducere Online <@link https://exducereonline.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use mod_quiz\local\access_rule_base;
use mod_quiz\quiz_settings;

/**
 *  Class that implements the access rule based on profile fields.
 *
 * @package    quizaccess_profilefields
 * @copyright  2025 Casen Xu <casenxu@exducereonline.com>
 * @copyright  Exducere Online <@link https://exducereonline.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_profilefields extends access_rule_base {

    /**
     * Return an appropriately configured instance of this rule if it is applicable
     * to the given quiz, otherwise return null.
     *
     * @param quiz_settings $quizobj information about the quiz in question.
     * @param int $timenow the time that should be considered as 'now'.
     * @param bool $canignoretimelimits whether the current user is exempt from
     *
     * @return self|null the rule, if applicable, else null.
     */
    public static function make($quizobj, $timenow, $canignoretimelimits) {
        if (!empty($quizobj->get_quiz()->profilefieldslistconditionsarray) AND
            $quizobj->get_quiz()->enable_quizaccess_profilefields) {
            return new self($quizobj, $timenow);
        } else {
            return null;
        }
    }

    /**
     * Whether the user should be blocked from starting a new attempt or continuing
     * an attempt now.
     * @return string false if access should be allowed, a message explaining the
     *      reason if access should be prevented.
     */
    public function prevent_access() {
        global $DB, $USER;

        // Get conditions from the database.
        list($inorequal, $params) = $DB->get_in_or_equal($this->quiz->profilefieldslistconditionsarray);
        $select = 'id ' . $inorequal;
        $conditions = $DB->get_records_select('quizaccess_profile_condition', $select, $params, 'sortorder ASC, name ASC');

        // Verify if there are conditions configured.
        foreach ($conditions as $condition) {
            $is_block_access = false;

            // Get the field data.
            $field = $DB->get_record('user_info_field', array('id' => $condition->fieldid), '*', MUST_EXIST);
            if(empty($field)) {
                continue;
            }

            // Get the user's data for this field.
            $userdata = $DB->get_record('user_info_data', array('userid' => $USER->id, 'fieldid' => $condition->fieldid));
            if(empty($userdata)) {
                $is_block_access = ($condition->missingfield === 'include') ? false : true;
                $user_field_value = '';
            } else {
                // Get the operator and value from the condition.
                $user_field_value = $userdata->data;
                switch ($condition->operator) {
                    case 'contains':
                        $is_block_access = (strpos($user_field_value, $condition->value) !== false);
                        break;
                    case 'notcontains':
                        $is_block_access = (strpos($user_field_value, $condition->value) === false);
                        break;
                    case 'equals':
                        $is_block_access = ($user_field_value == $condition->value);
                        break;
                    case 'containsi':
                        $is_block_access = (stripos($user_field_value, $condition->value) !== false);
                        break;
                    case 'notcontainsi':
                        $is_block_access = (stripos($user_field_value, $condition->value) === false);
                        break;
                    case 'isempty':
                        $is_block_access = empty($user_field_value);
                        break;
                    case 'isnotempty':
                        $is_block_access = !empty($user_field_value);
                        break;
                }
            }

            if($is_block_access) {
                $profilefields_block_icon = html_writer::tag('i', '', ['class' => 'fa fa-user-check']);
                $profilefields_block_text = !empty($condition->message) ? $condition->message : '';

                // Format the custom message to be displayed when access is blocked.
                if (empty($profilefields_block_text) || empty(json_decode($condition->message)->text)) {
                    $profilefields_block_text = get_string('default_block_text', 'quizaccess_profilefields');
                } else {
                    $profilefields_block_text = json_decode($condition->message)->text;
                }

                // Set the custom information adding to the message to be displayed.
                $items = [];
                $course = $DB->get_record('course', ['id' => $this->quiz->course]);
                $items[] = html_writer::tag('strong', get_string('user').": ").
                    "(".$USER->username.") ".$USER->firstname." ".$USER->lastname." - ".$USER->email;
                $items[] = html_writer::tag('strong', get_string('quizname', 'quiz_statistics').": "). $this->quiz->name;
                $items[] = html_writer::tag('strong', get_string('coursename', 'quiz_statistics').": "). $course->fullname;
                $items[] = html_writer::tag('strong', get_string('condition', 'quizaccess_profilefields').": "). $condition->name;
                $items[] = html_writer::tag('strong', get_string('value', 'quizaccess_profilefields').": "). $user_field_value;
                $items[] = html_writer::tag('strong',get_string('date').": ").userdate(time(), '%d %B %Y, %I:%M:%S %p');

                $content_info_text = html_writer::tag('div',"<span>$profilefields_block_icon</span> <span>$profilefields_block_text</span>", ['class' => 'flex flex-row']);
                $content_log_info = html_writer::tag('div',
                    html_writer::alist($items, [],'ul'),
                    ['class' => 'flex flex-row']
                );

                $message = html_writer::tag('div',
                    $content_info_text . $content_log_info,
                    ['class' => 'alert alert-danger text-left']
                );

                return $message;
            }
        }

        return false;
    }

    /**
     * Information, such as might be shown on the quiz view page, relating to this restriction.
     * There is no obligation to return anything. If it is not appropriate to tell students
     * about this rule, then just return ''.
     *
     * @return mixed a message, or array of messages, explaining the restriction
     *         (maybe '' if no message is appropriate).
     */
    public function description() {
        // Check if the plugin is enabled.
        $profilefields_info_icon = html_writer::tag('i', '', ['class' => 'fa fa-user-check']);
        $profilefields_info_text = get_string('profilefields_quiz_info', 'quizaccess_profilefields');
        $messages[] = html_writer::tag('div',
            "<span>$profilefields_info_icon</span> <span>$profilefields_info_text</span>",
            ['class' => 'alert alert-info text-left']
        );
        return $messages;
    }

    /**
     * Add any fields that this rule requires to the quiz settings form. This
     * method is called from {@see mod_quiz_mod_form::definition()}, while the
     * security section is being built.
     * @param mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param MoodleQuickForm $mform the wrapped MoodleQuickForm.
     */
    public static function add_settings_form_fields(
            mod_quiz_mod_form $quizform, MoodleQuickForm $mform) {
        global $DB;

        $pluginconfig = get_config('quizaccess_profilefields');

        $conditions = $DB->get_records_menu('quizaccess_profile_condition', [], 'sortorder ASC, name ASC', 'id, name');
        if (empty($conditions) || !$pluginconfig->enable_quizaccess_profilefields) {
            return;
        }

        $group = [];
        foreach ($conditions as $conditionid => $conditionname) {
            $group[] = $mform->createElement('checkbox', "profilefieldslistconditions[$conditionid]", '', $conditionname);
        }
        $mform->addGroup($group, 'profilefieldslistconditions',
            get_string('allowedconditions', 'quizaccess_profilefields'), '', false);
        $mform->addHelpButton('profilefieldslistconditions', 'allowedconditions', 'quizaccess_profilefields');

        if (!empty($pluginconfig->defaultconditions)) {
            $defaultconditions = explode(',', $pluginconfig->defaultconditions);
        } else {
            $defaultconditions = [];
        }
        foreach ($defaultconditions as $conditionid) {
            $mform->setDefault("profilefieldslistconditions[$conditionid]", 1);
        }
    }

    /**
     * Save any submitted settings when the quiz settings form is submitted. This
     * is called from {@see quiz_after_add_or_update()} in lib.php.
     * @param object $quiz the data from the quiz form, including $quiz->id
     *      which is the id of the quiz being saved.
     */
    public static function save_settings($quiz) {
        global $DB;

        $DB->delete_records('quizaccess_profile_fields', ['quizid' => $quiz->id]);
        if (!empty($quiz->profilefieldslistconditions)) {
            foreach ($quiz->profilefieldslistconditions as $conditionids => $unused) {
                $record = new stdClass();
                $record->quizid = $quiz->id;
                $record->conditions = $conditionids;
                $DB->insert_record('quizaccess_profile_fields', $record);
            }
        }
    }

    /**
     * Delete any rule-specific settings when the quiz is deleted. This is called
     *  from {@see quiz_delete_instance()} in lib.php.
     *
     * @param object $quiz the data from the database, including $quiz->id
     *       which is the id of the quiz being deleted.
     */
    public static function delete_settings($quiz) {
        global $DB;

        $DB->delete_records('quizaccess_profile_fields', ['quizid' => $quiz->id]);
    }

    /**
     * You can use this method to load any extra settings your plugin has that
     * cannot be loaded efficiently with get_settings_sql().
     * @param int $quizid the quiz id.
     * @return array setting value name => value. The value names should all
     *      start with the name of your plugin to avoid collisions.
     */
    public static function get_extra_settings($quizid) {
        global $DB;

        $conditions = [];
        $all_conditions = $DB->get_records('quizaccess_profile_condition');
        $used_conditions = $DB->get_records_menu('quizaccess_profile_fields', ['quizid' => $quizid], '', 'id, conditions');
        foreach ($all_conditions as $condition_id => $condition) {
            if (in_array($condition_id, $used_conditions)) {
                $conditions["profilefieldslistconditions[$condition_id]"] = 1;
            } else {
                $conditions["profilefieldslistconditions[$condition_id]"] = 0;
            }
        }
        $conditions['profilefieldslistconditionsarray'] = $used_conditions;
        $conditions['enable_quizaccess_profilefields'] = get_config('quizaccess_profilefields', 'enable_quizaccess_profilefields');
        return $conditions;
    }

}


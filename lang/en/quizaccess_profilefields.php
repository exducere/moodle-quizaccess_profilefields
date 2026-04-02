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
 * Strings for the quizaccess_profilefields plugin.
 *
 * @package    quizaccess_profilefields
 * @copyright  2025 Casen Xu <casenxu@exducereonline.com>
 * @copyright  2025 Exducere Online {@link https://exducereonline.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['accessdenied'] = 'Access denied';
$string['action_block'] = 'Block access';
$string['action_warn'] = 'Warn but allow access';
$string['addcondition'] = 'Add another condition';
$string['allowedconditions'] = 'Allowed conditions';
$string['allowedconditions_help'] = 'Select the profile field conditions that will be applied to this quiz. Users must meet all selected conditions to access the quiz.';
$string['condition'] = 'Condition';
$string['conditionline'] = 'Field "{$a->field}" {$a->operator} "{$a->value}"';
$string['conditionsheader'] = 'This quiz has the following access conditions:';
$string['custommessage'] = 'Custom message';
$string['custommessage_default'] = 'You do not meet the required profile conditions to access this quiz.';
$string['custommessage_desc'] = 'Custom message to display when access is denied. If empty, a default message will be shown.';
$string['custommessage_help'] = 'This message will be displayed to users who do not meet the profile field conditions.';
$string['default_block_text'] = 'You do not have permission to access this quiz due to the conditions set for your profile.';
$string['defaultaction'] = 'Default action';
$string['defaultaction_desc'] = 'Default action to take when a condition is not met.';
$string['defaultaction_help'] = 'This setting determines what happens when a user does not meet the profile field conditions.';
$string['defaultconditions'] = 'Default conditions';
$string['defaultconditions_desc'] = 'Default conditions to apply to new quizzes.';
$string['editcondition'] = 'Edit profile condition';
$string['enabled'] = 'Enable profile condition';
$string['enabled_desc'] = 'If enabled, access to quizzes can be restricted based on user profile fields.';
$string['error_invalidfield'] = 'Invalid profile field';
$string['error_novalue'] = 'You must enter a value';
$string['globalsettings'] = 'Global settings';
$string['manageconditions'] = 'Manage profile conditions';
$string['missingfield_action'] = 'When profile field is missing';
$string['missingfield_action_help'] = 'This setting determines what happens when a user does not have the profile field configured.';
$string['missingfield_exclude'] = 'Exclude user (deny access)';
$string['missingfield_include'] = 'Include user (allow access)';
$string['missingfieldline'] = 'When field "{$a->field}" is missing: {$a->action}';
$string['noprofilefields'] = 'No profile fields available';
$string['noprofilefields_desc'] = 'To use this plugin, you need to create custom profile fields first.';
$string['operator'] = 'Condition';
$string['operator_contains'] = 'Contains';
$string['operator_containsi'] = 'Contains (case insensitive)';
$string['operator_equals'] = 'Equals';
$string['operator_isempty'] = 'Is empty';
$string['operator_isnotempty'] = 'Is not empty';
$string['operator_notcontains'] = 'Does not contain';
$string['operator_notcontainsi'] = 'Does not contain (case insensitive)';
$string['plugindisabled'] = 'Plugin disabled';
$string['plugindisabled_desc'] = 'The profile condition plugin is currently disabled in the global settings.';
$string['pluginname'] = 'Profile field condition quiz access rule';
$string['privacy:metadata'] = 'The Profile field condition quiz access rule plugin does not store any personal data.';
$string['profilefield'] = 'Profile field';
$string['profilefields_quiz_info'] = 'Profile field validation is enabled. Once the conditions are met, the user will be able to access the quiz. If not met, a custom message will be shown or access will be blocked according to the configuration.';
$string['profilefieldsheader'] = 'Profile field conditions';
$string['remove'] = 'Remove';
$string['unknownfield'] = 'Unknown field';
$string['value'] = 'Value';

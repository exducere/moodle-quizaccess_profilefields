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
        $DB->delete_records('quizaccess_profilefields_quizzes', ['conditions' => $id]);
        $DB->delete_records('quizaccess_profilefields_conditions', ['id' => $id]);
        quizaccess_profilefields_reorder_conditions();
        break;

    case 'up':
    case 'down':
        quizaccess_profilefields_swap_condition($id, $action);
        break;
}

redirect(new moodle_url('/admin/settings.php', ['section' => 'modsettingsquizcatprofilefields']));

/**
 * Move a condition one position up or down by swapping its sortorder with its neighbour.
 *
 * All records are loaded once and the target/neighbour are located in memory; the two
 * update_record() calls run outside any loop to avoid per-iteration database writes.
 *
 * @param int $id the id of the condition to move.
 * @param string $direction either 'up' or 'down'.
 */
function quizaccess_profilefields_swap_condition($id, $direction) {
    global $DB;

    // Ordering the list in the direction of travel means the neighbour to swap with
    // is always the item immediately before the target in this in-memory array.
    $order = ($direction === 'up') ? 'sortorder ASC, name ASC' : 'sortorder DESC, name DESC';
    $conditions = array_values($DB->get_records('quizaccess_profilefields_conditions', [], $order));

    $target = null;
    $neighbour = null;
    foreach ($conditions as $index => $condition) {
        if ($condition->id == $id) {
            $target = $condition;
            $neighbour = $index > 0 ? $conditions[$index - 1] : null;
            break;
        }
    }

    // Nothing to do if the condition was not found or it is already at the edge.
    if ($target === null || $neighbour === null) {
        return;
    }

    [$target->sortorder, $neighbour->sortorder] = [$neighbour->sortorder, $target->sortorder];
    $DB->update_record('quizaccess_profilefields_conditions', $target);
    $DB->update_record('quizaccess_profilefields_conditions', $neighbour);
    quizaccess_profilefields_reorder_conditions();
}

/**
 * Corrige el orden de clasificación.
 */
function quizaccess_profilefields_reorder_conditions() {
    global $DB;
    $conditions = $DB->get_records('quizaccess_profilefields_conditions', [], 'sortorder ASC, name ASC');
    $current = 1;
    foreach ($conditions as $condition) {
        if ($condition->sortorder != $current) {
            $condition->sortorder = $current;
            $DB->update_record('quizaccess_profilefields_conditions', $condition);
        }
        $current++;
    }
}


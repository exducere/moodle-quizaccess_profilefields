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
 * Upgrade steps for the quizaccess_profilefields plugin.
 *
 * @package    quizaccess_profilefields
 * @copyright  2025 Casen Xu <casenxu@exducereonline.com>
 * @copyright  2025 Exducere Online {@link https://exducereonline.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute the quizaccess_profilefields upgrade steps.
 *
 * @param int $oldversion the version we are upgrading from.
 * @return bool
 */
function xmldb_quizaccess_profilefields_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2026070100) {

        // Rename the tables so they start with the full Frankenstyle component name.
        $oldconditions = new xmldb_table('quizaccess_profile_condition');
        if ($dbman->table_exists($oldconditions)) {
            $dbman->rename_table($oldconditions, 'quizaccess_profilefields_conditions');
        }

        $oldquizzes = new xmldb_table('quizaccess_profile_fields');
        if ($dbman->table_exists($oldquizzes)) {
            $dbman->rename_table($oldquizzes, 'quizaccess_profilefields_quizzes');
        }

        // Profilefields savepoint reached.
        upgrade_plugin_savepoint(true, 2026070100, 'quizaccess', 'profilefields');
    }

    return true;
}

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

use admin_setting_configmulticheckbox;

/**
 * Class Custom admin config setting for editing profile fields conditions.
 *
 * @package    quizaccess_profilefields
 * @copyright  2025 Casen Xu <casenxu@exducereonline.com>
 * @copyright  Exducere Online <@link https://exducereonline.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_config_setting_profilefields_list extends admin_setting_configmulticheckbox {

    /**
     * Constructor
     * @param string $name unique ascii name
     * @param string $visiblename localised
     * @param string $description long localized info
     * @param array $defaultsetting array of selected items
     * @param array $choices array of $value=>$label for each list item
     */
    public function __construct($name, $visiblename, $description, $defaultsetting, $choices) {
        parent::__construct($name, $visiblename, $description, $defaultsetting, $choices);
    }

    /**
     * Save the setting value to the database.
     *
     * @param $data
     */
    public function write_setting($data) {
        if (!is_array($data)) {
            return '';
        }

        $result = array();
        foreach ($data as $key => $value) {
            if ($value) {
                $result[] = $key;
            }
        }

        return $this->config_write($this->name, implode(',', $result)) ? '' : get_string('errorsetting', 'admin');
    }

    /**
     * Returns the setting value.
     *
     * @return array|null
     */
    public function get_setting() {
        $result = array();
        $value = $this->config_read($this->name);

        if (is_null($value)) {
            return null;
        }

        if ($value === '') {
            return array();
        }

        $values = explode(',', $value);
        foreach ($values as $key) {
            $result[$key] = 1;
        }

        return $result;
    }
}


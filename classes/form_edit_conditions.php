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
namespace quizaccess_profilefields;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

use moodleform;

/**
 * Class Form for editing profile fields conditions.
 *
 * @package    quizaccess_profilefields
 * @copyright  2025 Casen Xu <casenxu@exducereonline.com>
 * @copyright  Exducere Online <@link https://exducereonline.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class form_edit_conditions extends moodleform {

    protected function definition() {
        global $DB;

        $mform = $this->_form;

        $mform->addElement('header', 'header', get_string('editcondition', 'quizaccess_profilefields'));

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name', get_string('name'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', get_string('required'), 'required', null, 'client');

        // Profile field selection.
        $profilefields = array();
        $fields = $DB->get_records('user_info_field', null, 'name ASC', 'id, name, shortname, datatype');
        foreach ($fields as $field) {
            $profilefields[$field->id] = $field->name . ' (' . $field->shortname . ')';
        }
        $mform->addElement('select', 'fieldid', get_string('profilefield', 'quizaccess_profilefields'), $profilefields);
        $mform->addRule('fieldid', get_string('required'), 'required', null, 'client');

        // Logical operator.
        $operators = [
            'contains' => get_string('operator_contains', 'quizaccess_profilefields'),
            'notcontains' => get_string('operator_notcontains', 'quizaccess_profilefields'),
            'equals' => get_string('operator_equals', 'quizaccess_profilefields'),
            'containsi' => get_string('operator_containsi', 'quizaccess_profilefields'),
            'notcontainsi' => get_string('operator_notcontainsi', 'quizaccess_profilefields'),
            'isempty' => get_string('operator_isempty', 'quizaccess_profilefields'),
            'isnotempty' => get_string('operator_isnotempty', 'quizaccess_profilefields')
        ];
        $mform->addElement('select', 'operator', get_string('operator', 'quizaccess_profilefields'), $operators);
        $mform->addRule('operator', get_string('required'), 'required', null, 'client');

        // Value.
        $mform->addElement('text', 'value', get_string('value', 'quizaccess_profilefields'), ['size' => 50]);
        $mform->setType('value', PARAM_TEXT);

        // Hacer que el campo de valor sea opcional cuando el operador es "está vacío" o "no está vacío"
        $mform->disabledIf('value', 'operator', 'eq', 'isempty');
        $mform->disabledIf('value', 'operator', 'eq', 'isnotempty');

        // Action when the profile field is missing.
        $missingaction = [
            'exclude' => get_string('missingfield_exclude', 'quizaccess_profilefields'),
            'include' => get_string('missingfield_include', 'quizaccess_profilefields')
        ];
        $mform->addElement('select', 'missingfield_action',
            get_string('missingfield_action', 'quizaccess_profilefields'),
            $missingaction);
        $mform->addHelpButton('missingfield_action', 'missingfield_action', 'quizaccess_profilefields');
        $mform->setDefault('missingfield_action', 'exclude');

        // Editor options.
        $editor_options = array(
            'maxfiles' => 0, // Número máximo de archivos adjuntos (0 si no se necesitan archivos adjuntos)
            'maxbytes' => 0, // Tamaño máximo de archivos adjuntos
            'trusttext' => true, // Permitir texto de confianza para contenido sin filtro
            'enable_filemanagement' => false, // Deshabilitar gestión de archivos si no es necesaria
        );
        // Add the editor.
        $mform->addElement('editor', 'message', get_string('termsconditions', 'quizaccess_termsconditions'), 'sss', $editor_options);
        $mform->setType('message', PARAM_RAW);

        $this->add_action_buttons(true);
    }

    /**
     * Validation method for the form.
     *
     * @param array $data Datos del formulario.
     * @param array $files Archivos del formulario.
     * @return array Errores de validación.
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        global $DB;
        if (!$DB->record_exists('user_info_field', ['id' => $data['fieldid']])) {
            $errors['fieldid'] = get_string('error_invalidfield', 'quizaccess_profilefields');
        }

        // Check if the operator is valid.
        if (!in_array($data['operator'], ['isempty', 'isnotempty']) && empty($data['value'])) {
            $errors['value'] = get_string('error_novalue', 'quizaccess_profilefields');
        }

        return $errors;
    }
}


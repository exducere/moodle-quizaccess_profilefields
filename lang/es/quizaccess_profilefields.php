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
 * @copyright  Exducere Online <@link https://exducereonline.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Regla de acceso por campo de perfil';
$string['privacy:metadata'] = 'El plugin de regla de acceso a cuestionarios por campo de perfil no almacena ningún dato personal.';

// Admin settings
$string['enabled'] = 'Habilitar condición de perfil';
$string['enabled_desc'] = 'Si está habilitado, el acceso a los cuestionarios puede restringirse según los campos de perfil del usuario.';
$string['defaultaction'] = 'Acción predeterminada';
$string['defaultaction_desc'] = 'Acción predeterminada a tomar cuando no se cumple una condición.';
$string['action_block'] = 'Bloquear acceso';
$string['action_warn'] = 'Advertir pero permitir acceso';
$string['custommessage'] = 'Mensaje personalizado';
$string['custommessage_desc'] = 'Mensaje personalizado para mostrar cuando se deniega el acceso. Si está vacío, se mostrará un mensaje predeterminado.';
$string['custommessage_default'] = 'No cumples con las condiciones de perfil requeridas para acceder a este cuestionario.';
$string['globalsettings'] = 'Configuración global';

// Form fields
$string['profilefieldsheader'] = 'Condiciones de campo de perfil';
$string['profilefield'] = 'Campo de perfil';
$string['operator'] = 'Condición';
$string['value'] = 'Valor';
$string['addcondition'] = 'Añadir otra condición';
$string['remove'] = 'Eliminar';
$string['noprofilefields'] = 'No hay campos de perfil disponibles';
$string['noprofilefields_desc'] = 'Para usar este plugin, primero debes crear campos de perfil personalizados.';
$string['plugindisabled'] = 'Plugin deshabilitado';
$string['plugindisabled_desc'] = 'El plugin de condición de perfil está actualmente deshabilitado en la configuración global.';

// Operators
$string['operator_contains'] = 'Contiene';
$string['operator_notcontains'] = 'No contiene';
$string['operator_equals'] = 'Es igual a';
$string['operator_containsi'] = 'Contiene (sin distinción de mayúsculas/minúsculas)';
$string['operator_notcontainsi'] = 'No contiene (sin distinción de mayúsculas/minúsculas)';
$string['operator_isempty'] = 'Está vacío';
$string['operator_isnotempty'] = 'No está vacío';

// Missing field actions
$string['missingfield_action'] = 'Cuando el campo de perfil no existe';
$string['missingfield_action_help'] = 'Esta configuración determina qué sucede cuando un usuario no tiene el campo de perfil configurado.';
$string['missingfield_exclude'] = 'Denegar acceso al usuario';
$string['missingfield_include'] = 'Permitir acceso al usuario';
$string['missingfieldline'] = 'Cuando el campo "{$a->field}" no existe: {$a->action}';

// Messages
$string['accessdenied'] = 'Acceso denegado';
$string['conditionsheader'] = 'Este cuestionario tiene las siguientes condiciones de acceso:';
$string['conditionline'] = 'Campo "{$a->field}" {$a->operator} "{$a->value}"';
$string['error_novalue'] = 'Debes ingresar un valor';
$string['error_invalidfield'] = 'Campo de perfil no válido';

// Help strings
$string['defaultaction_help'] = 'Esta configuración determina qué sucede cuando un usuario no cumple con las condiciones de campo de perfil.';
$string['custommessage_help'] = 'Este mensaje se mostrará a los usuarios que no cumplan con las condiciones de campo de perfil.';

// New strings for the admin interface
$string['manageconditions'] = 'Administrar condiciones de perfil';
$string['editcondition'] = 'Editar condición de perfil';
$string['condition'] = 'Condición';
$string['defaultconditions'] = 'Condiciones predeterminadas';
$string['defaultconditions_desc'] = 'Condiciones predeterminadas para aplicar a nuevos cuestionarios.';
$string['allowedconditions'] = 'Validación de campos de perfil permitidos';
$string['allowedconditions_help'] = 'Seleccione las condiciones de campo de perfil que se aplicarán a este cuestionario. Los usuarios deben cumplir con todas las condiciones seleccionadas para acceder al cuestionario.';
$string['unknownfield'] = 'Campo desconocido';

$string['profilefields_quiz_info'] = 'La <strong>validación por campos de perfil</strong> esta habilitada. Una vez que se cumplan las condiciones de validación del usuario, podrá acceder al cuestionario.';
$string['default_block_text'] = 'No tiene <strong>permiso para acceder</strong> a este cuestionario debido a las condiciones establecidas para el usuario.';
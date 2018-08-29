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

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->libdir . '/gradelib.php');

class block_showgrade_edit_form extends block_edit_form {

    // Only get categories which has GRADE_AGGREGATE_SUM as aggregation method.
    private static function get_all_categories() {
        global $DB, $COURSE;

        $categories = []; 
        $categoriesrs = grade_category::fetch_all(array('courseid' => $COURSE->id, 'aggregation' => GRADE_AGGREGATE_SUM));

        if ($categoriesrs != null) {
            foreach ($categoriesrs as $record) {
                if ($record->fullname == "?") {
                    $categories[$record->id] = get_string('coursetotal', 'block_showgrade');
                } else {
                    $categories[$record->id] = $record->fullname;
                }
            }
         }

         return $categories;
    }

    protected function specific_definition($mform) {

        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $categories = self::get_all_categories();
        if ($categories) {

            // Section header title according to language file.
            $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_showgrade'));
            $mform->setType('config_title', PARAM_TEXT);
            $mform->addElement('select', 'config_category', get_string('category', 'block_showgrade'), $categories);
            $mform->addElement('advcheckbox', 'config_enablemaxpoints', get_string('enablemaxpoints', 'block_showgrade'));
            $mform->addElement('advcheckbox', 'config_enablecompletion', get_string('enablecompletion', 'block_showgrade'));
            $mform->addElement('advcheckbox', 'config_enablelevels', get_string('enablelevels', 'block_showgrade'));
            $mform->addElement('text', 'config_pointslevel', get_string('pointslevelup', 'block_showgrade'));
            $mform->setDefault('config_pointslevel', '100');
            $mform->disabledIf('config_pointslevel', 'config_enablelevels');
            $mform->setType('config_pointslevel', PARAM_INT);
        } else {
            $mform->addElement('html',
                '<div class="showgrade-error">' . get_string('no_category_error', 'block_showgrade') . '</div>');
        }
    }
}

<?php

global $CFG;
require_once($CFG->libdir . '/gradelib.php');


class block_showgrade_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        global $DB, $COURSE;

        // Only get categories which has GRADE_AGGREGATE_SUM as
        $categoriesRS = grade_category::fetch_all(array('courseid'=>$COURSE->id, 'aggregation' => GRADE_AGGREGATE_SUM));

        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

	if ($categoriesRS != null) {
            foreach($categoriesRS as $record) {
                if ($record->fullname == "?") {
                    $categories[$record->id] = get_string('coursetotal', 'block_showgrade');
                } else {
                    $categories[$record->id] = $record->fullname;
                }
            }

            // Section header title according to language file.
            $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_showgrade'));
            $mform->setType('config_title', PARAM_TEXT);
            
            $mform->addElement('select', 'config_category', get_string('category', 'block_showgrade'), $categories);
            
            $mform->addElement('advcheckbox', 'config_enablemaxpoints', get_string('enablemaxpoints', 'block_showgrade'));
            $mform->addElement('advcheckbox', 'config_enablecompletion', get_string('enablecompletion', 'block_showgrade'));
            $mform->addElement('advcheckbox', 'config_enablelevels', get_string('enablelevels', 'block_showgrade'));
            
            $points = [100=>100, 200=>200,300=>300,400=>400,500=>500,1000=>1000,2000=>2000,5000=>5000];
            
            $mform->addElement('select', 'config_pointslevel', get_string('pointslevelup', 'block_showgrade'), $points);
            $mform->disabledIf('config_levels', 'config_enablelevels');
            $mform->disabledIf('config_pointslevel','config_enablelevels');
	}
	else {
	    $mform->addElement('html', '<div class="showgrade-error">' . get_string('no_category_error', 'block_showgrade') . '</div>');
	}
    }
}

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

require_once('../../config.php');
require_once('./badgelevel_form.php');

require_login();

$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$return = optional_param('returnurl', 0, PARAM_LOCALURL);

$url = new moodle_url('/blocks/showgrade/badgelevel.php', array('courseid' => $courseid, 'blockid' => $blockid));
$courseurl = new moodle_url('/course/view.php', array('id' => $courseid));

$PAGE->set_context(context_user::instance($USER->id));
$PAGE->set_url($url);

$PAGE->set_pagelayout('incourse');

$db = new badgelevel_db($courseid, $blockid);

$form = new badgelevel_form($db);

switch ($form->action) {
    case 'update':
        $form->update();
        redirect($url);
        break;
    case 'delete':
        $form->delete();
        redirect($url);
        break;
    case 'add':
        $form->add();
        redirect($url);
        break;
    case 'cancel':
        redirect($courseurl);
        break;
    default:
        echo $OUTPUT->header();
        $form->display();
        echo $OUTPUT->footer();
}

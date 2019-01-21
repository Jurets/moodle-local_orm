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
 * Idiorm Demo
 *
 * @package    local_orm
 * @copyright  2019 Jurets <jurets75.gmail@com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");

use local_orm\base as orm;

$courses = orm::for_table('course')
    ->table_alias('c')
    ->select('c.*')
    ->select('cat.name', 'catname')
    ->join('course_categories', ['c.category', '=', 'cat.id'], 'cat')
    ->where_gte('id', SITEID)
    ->find_many();

foreach ($courses as $course) {
    echo html_writer::div($course->shortname . ': ' . $course->startdate . ' - ' . $course->enddate);
    echo html_writer::div($course->catname);
}

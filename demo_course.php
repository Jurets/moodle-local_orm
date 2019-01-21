<?php

// ------------------- //
// --- Idiorm Demo --- //
// ------------------- //

// Note: This is just about the simplest database-driven webapp it's possible to create
// and is designed only for the purpose of demonstrating how Idiorm works.

// In case it's not obvious: this is not the correct way to build web applications!

// Require the idiorm file

require_once("../../config.php");

use local_orm\entities\course;
use local_orm\course_module;
use local_orm\base as orm;

$courses = course::where_gte('id', SITEID)->find_many();

foreach ($courses as $course) {
    echo html_writer::div($course->shortname . ': ' . $course->startdate . ' - ' . $course->enddate);
}
echo '<br><br>';

$modules = orm::for_table('course')
    ->select_many(['courseid' => 'c.id'], ['coursename' => 'c.shortname'], 'cm.*', ['modulename' => 'm.name'], ['pagename' => 'p.name'])
    ->table_alias('c')
    ->join('course_modules', ['c.id', '=', 'cm.course'], 'cm')
    ->join('modules', ['cm.module', '=', 'm.id'], 'm')
    ->join('page', ['cm.instance', '=', 'p.id'], 'p')
    ->where_equal('c.id', 3)
    ->where_in('m.name', ['page'])
    ->find_many();

$page = $modules[0];
echo html_writer::div($page->coursename);
echo html_writer::div($page->modulename . ' - ' . $page->pagename);
echo '<br><br>';

// With associations
$cm = course_module::where_gte('course', $page->courseid)->find_one();
$module = $cm->module()->find_one();
echo html_writer::div($cm->module . ' - ' . $module->name);
<?php

// ------------------- //
// --- Idiorm Demo --- //
// ------------------- //

// Note: This is just about the simplest database-driven webapp it's possible to create
// and is designed only for the purpose of demonstrating how Idiorm works.

// In case it's not obvious: this is not the correct way to build web applications!

// Require the idiorm file

require_once("../../config.php");

use local_orm\model;

$courses = model::factory('course')
    ->where_gte('id', SITEID)
    ->find_many();

foreach ($courses as $course) {
    echo html_writer::div($course->shortname . ': ' . $course->startdate . ' - ' . $course->enddate);
}

$module = model::factory('modules')->where('name', 'glossary')->find_array();
var_dump($module);
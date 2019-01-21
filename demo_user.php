<?php

// ------------------- //
// --- Idiorm Demo --- //
// ------------------- //

// Note: This is just about the simplest database-driven webapp it's possible to create
// and is designed only for the purpose of demonstrating how Idiorm works.

// In case it's not obvious: this is not the correct way to build web applications!

// Require the idiorm file

require_once("../../config.php");

global $CFG;

use local_orm\model;
use local_orm\user;
use local_orm\base as orm;

$users = user::where('confirmed', 1)->find_many();
foreach ($users as $user) {
    echo html_writer::div($user->username . ': ' . $user->firstname . ' - ' . $user->email);
}

$user = \local_orm\user::where_equal('username', 'john')->find_one();
echo html_writer::div($user->username);

$user = model::factory('user')->filter('current')->find_one();
echo html_writer::div(($user ? $user->username : 'not logged'));

$user = \local_orm\entities\user::select('*')->filter('current')->find_one();
echo html_writer::div(($user ? $user->username : 'not logged'));

$record = orm::for_table('user')
    ->table_alias('u')
    ->select_many('u.id', 'u.username')
    ->where_equal('id', 2)
    ->find_one();
echo html_writer::div(($record ? $record->username : 'not logged'));
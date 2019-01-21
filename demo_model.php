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

use local_orm\user;

$users = user::where('confirmed', 1)
    ->find_many();
foreach ($users as $user) {
    echo html_writer::div($user->username . ': ' . $user->firstname . ' - ' . $user->email);
}

$user = \local_orm\user::where_equal('username', 'john')->find_one();
echo $user->username;

$user = \local_orm\user::where_equal('username', 'john')->find_one();
echo $user->username;

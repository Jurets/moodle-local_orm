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
 * Inherited ORM class
 *
 * @package    local_orm
 * @copyright  2019 Jurets <jurets75.gmail@com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");

require_login();

use local_orm\base as orm;
use local_orm\model;
use local_orm\user;
use local_orm\entities\course;
use local_orm\course_module;

/*$courses = course::where_gte('id', SITEID)->find_many();

$modules = orm::for_table('course')
    ->select_many(['courseid' => 'c.id'], ['coursename' => 'c.shortname'], 'cm.*',
        ['modulename' => 'm.name'], ['pagename' => 'p.name'])
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

$courses = course::where_gte('id', SITEID)->find_many();

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

$users = user::where('confirmed', 1)->find_many();
foreach ($users as $user) {
    echo html_writer::div($user->username . ': ' . $user->firstname . ' - ' . $user->email);
}

$user = \local_orm\user::where_equal('username', 'john')->find_one();

echo html_writer::div(($user ? $user->username : 'not logged'));

$user = \local_orm\entities\user::select('*')->filter('current')->find_one();
echo html_writer::div(($user ? $user->username : 'not logged'));

$record = orm::for_table('user')
    ->table_alias('u')
    ->select_many('u.id', 'u.username')
    ->where_equal('id', 2)
    ->find_one();
echo html_writer::div(($record ? $record->username : 'not logged'));
*/

?>

<html>
    <head>
        <title>Moodle Idiorm & Paris Demo</title>
    </head>

    <body>
    
        <h1>Moodle Idiorm & Paris Demo</h1>
        <?php
        if (!is_siteadmin()) {
            echo 'This demo is only for site administrators! Try to re-login as site admin!';
        } else {
            // User model.
            $modeluser = user::select(['id', 'name' => 'c.username'])
                ->table_alias('c')
                ->find_many();
            $modeluser = model::factory('user')->where_in('username', ['admin', 'guest']);
            $users = $modeluser->find_many();
            ?>
            <h2>Use model factory method: User List of (<?php echo $modeluser->count(); ?> users)</h2>
            <ul>
                <?php foreach ($users as $user) { ?>
                    <li>
                        <strong><?php echo $user->name ?></strong>
                        <a href="mailto:<?php echo $user->email; ?>"><?php echo $user->email; ?></a>
                    </li>
                <?php } ?>
            </ul>

            <h2>Use model factory method: Current User</h2>
            <?php $user = model::factory('user')->filter('current')->find_one();?>
            <p><?php
            if ($user) {
                echo $user->username;
            }
            ?></p>

        <?php } ?>
    </body>
</html>

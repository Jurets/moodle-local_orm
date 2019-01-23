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
 * Description of file querybuilder_test
 *
 * @package    local_orm
 * @copyright  2019 Sebale
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use local_orm\base as orm;

/**
 * Class querybuilder_test
 * @package    local_orm
 * @copyright  2019 Sebale
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_orm_modelcourse_testcase extends advanced_testcase {

    const COURSE_SHORTNAME = 'TestCourse';
    const COURSE_PAGES = 3;
    const COURSE_LESSONS = 2;

    protected $user;
    protected $course;
    protected $pages = [];
    protected $contexts = [];
    protected $modules = [];

    /**
     * @var testing_data_generator
     */
    protected $generator;

    /**
     *
     */
    public function setUp() {
        $this->resetAfterTest(true);
        $this->preventResetByRollback();

        $this->generator = $this->getDataGenerator();
        $this->user = $this->generator->create_user(['email' => 'learner@example.com', 'username' => 'learner']);

        $this->course = $this->generator->create_course([
            'shortname' => self::COURSE_SHORTNAME,
            'enablecompletion' => 1
        ]);
        $this->generator->enrol_user($this->user->id, $this->course->id, 'student', 'manual');

        $this->create_modules('page', self::COURSE_PAGES);
        $this->create_modules('forum', self::COURSE_LESSONS);
    }

    /**
     * Create number modules of type
     *
     * @param string $type
     * @param int $number
     */
    protected function create_modules(string $type, int $number) {
        $params = ['course' => $this->course->id];
        $options = ['completion' => 2, 'completionview' => 1];
        for ($item = 1; $item <= $number; $item++) {
            $module = $this->generator->create_module($type, $params, array_merge($options, ['name' => $type.$item]));
            $this->pages[$module->id] = $module;
            $this->contexts[$module->id] = context_module::instance($module->cmid);
            $this->modules[$module->id] = get_coursemodule_from_instance($type, $module->id);
        }
    }

    /**
     * Check field alias, row method
     */
    public function test_course() {
        $record = orm::for_table('course')
            ->table_alias('c')
            ->select_many(['courseid' => 'c.id'], ['coursename' => 'c.shortname'])
            ->where_equal('c.id', $this->course->id)
            ->find_one();

        $this->assertEquals(self::COURSE_SHORTNAME, $record->coursename);
    }

    /**
     * Check field alias, row method
     */
    public function test_modules() {
        $pages = orm::for_table('course')
            ->table_alias('c')
            ->join('course_modules', ['c.id', '=', 'cm.course'], 'cm')
            ->join('modules', ['cm.module', '=', 'm.id'], 'm')
            ->select_many('cm.*', ['modulename' => 'm.name'])
            ->where_equal('c.id', $this->course->id)
            ->where_in('m.name', ['page'])
            ->find_many();

        $this->assertEquals(self::COURSE_PAGES, count($pages));
        if (isset($pages[0])) {
            $page = $pages[0];
            $this->assertEquals('page', $page->modulename);
        }
    }

}
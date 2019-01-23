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
class local_orm_querybuilder_testcase extends basic_testcase {

    /**
     * Check field alias, row method
     */
    public function test_selectadminuser() {
        $record = orm::for_table('user')
            ->table_alias('u')
            ->select_many('u.id', 'u.username')
            ->where_equal('id', 2)
            ->find_one();

        $this->assertInstanceOf('orm', $record);
        $this->assertEquals('admin', $record->username);
    }

    /**
     * Check input params merging and count method
     */
    public function test_userscount() {
        $record = orm::for_table('user')
            ->select_expr('COUNT(*)', 'count')
            ->find_one();

        $this->assertEquals($record->count, 2);
    }

}
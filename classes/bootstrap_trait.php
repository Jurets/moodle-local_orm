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
 * Moodle ORM implementation
 *
 * @package    local_orm
 * @copyright  2019 Jurets <jurets75.gmail@com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_orm;

use ORM;
use Model as ModelParis;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . "/local/orm/vendor/j4mie/idiorm/idiorm.php");
require_once($CFG->dirroot . "/local/orm/vendor/j4mie/paris/paris.php");

$configstr = 'mysql:host=' . $CFG->dbhost . ';dbname=' . $CFG->dbname;
ORM::configure($configstr);
ORM::configure('username', $CFG->dbuser);
ORM::configure('password', $CFG->dbpass); //$db = ORM::get_db();

//ModelParis::$auto_prefix_models = $CFG->prefix;
ModelParis::$short_table_names = true;

/**
 * Base trait bootstrap_trait
 * - Use base trait to avoid code duplication
 * - Place any methods code here
 *
 * @package local_orm
 */
trait bootstrap_trait {

    /**
     * Concat table prefix, from moodle db config
     *
     * @param string $table_name
     * @return string
     */
    protected static function table_prefix(string $table_name): string {
        global $CFG;
        if (defined('PHPUNIT_TEST') && PHPUNIT_TEST) {
            $prefix = $CFG->phpunit_prefix;
        } else {
            $prefix = $CFG->prefix;
        }
        return $prefix . $table_name;
    }

}
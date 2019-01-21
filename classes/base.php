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

namespace local_orm;

use ORM;

require_once('bootstrap_trait.php');

/**
 * Inherited ORM class
 *
 * @package    local_orm
 * @copyright  2019 Jurets <jurets75.gmail@com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class base extends ORM {

    // Use base trait to avoid code duplication
    use bootstrap_trait;

    public static function for_table($table_name, $connection_name = self::DEFAULT_CONNECTION) {
        self::_setup_db($connection_name);
        return new self(self::table_prefix($table_name), array(), $connection_name);
    }

    /**
     * Add a simple JOIN source to the query
     */
    public function join($table, $constraint, $table_alias=null) {
        return $this->_add_join_source("", self::table_prefix($table), $constraint, $table_alias);
    }

}
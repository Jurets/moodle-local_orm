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
 * User model
 * - predifined to supply most common user operations
 *
 * @package    local_orm
 * @copyright  2019 Jurets <jurets75.gmail@com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_orm;


class user extends model {


    /**
     * Return current user id
     *
     * @return int
     */
    protected static function current_userid(): int {
        global $USER;
        return $USER->id;
    }

    //public static $_table = 'mdl_user';

    public static function current($orm) {
        return $orm->where('id', self::current_userid());
    }
}
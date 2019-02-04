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
 * Abstract class model
 * - Used to incapsulate interaction with moodle components
 *
 * @package    local_orm
 * @copyright  2019 Jurets <jurets75.gmail@com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_orm;

use ORMWrapper as wrapper;
use Model as ModelParis;

defined('MOODLE_INTERNAL') || die();

require_once('bootstrap_trait.php');

/**
 * Abstract class model
 *
 * @package local_orm
 * @class model
 */
abstract class model extends ModelParis {

    use bootstrap_trait;

    /**
     * Overrided to provide late static binding
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters) {
        if (function_exists('get_called_class')) {
            $model = static::factory(get_called_class());
            return call_user_func_array(array($model, $method), $parameters);
        }
    }

    /**
     * Overrided
     *
     * @param string $classname
     * @param null $connectionname
     * @return wrapper
     */
    public static function factory($classname, $connectionname = null) {
        $tablename = static::_get_table_name($classname);

        $parsed = explode('\\', $classname);
        if (count($parsed) == 1) {
            $namespace = $parsed = explode('\\', get_called_class());
            array_pop($namespace);
            $namespace = implode('\\', $namespace);
            $classname = "\\$namespace\\$classname";
        }

        if ($connectionname == null) {
            $connectionname = static::_get_static_property(
                $classname,
                '_connection_name',
                wrapper::DEFAULT_CONNECTION
            );
        }
        $tablename = static::table_prefix($tablename);
        $wrapper = wrapper::for_table($tablename, $connectionname);
        $wrapper->set_class_name($classname);
        $wrapper->use_id_column(static::_get_id_column_name($classname));
        return $wrapper;
    }

    /**
     * Overrided
     *
     * @param  string $associatedclassname
     * @param  null|string $foreignkeyname
     * @param  null|string $foreignkeynameincurrentmodelstable
     * @param  null|string $connectionname
     * @return ORMWrapper
     */
    protected function _has_one_or_many($associatedclassname, $foreignkeyname = null,
                                        $foreignkeynameincurrentmodelstable = null, $connectionname = null) {
        $basetablename = self::_get_table_name(get_class($this));
        $foreignkeyname = self::_build_foreign_key_name($foreignkeyname, $basetablename);

        $wherevalue = ''; // Value of foreign_table.{$foreign_key_name} we're looking for.
        // Where foreign_table is the actual database table in the associated model.

        if (is_null($foreignkeynameincurrentmodelstable)) {
            // Match foreign_table.{$foreign_key_name} with the value of {$this->_table}.{$this->id()} .
            $wherevalue = $this->id();
        } else {
            // Match foreign_table.{$foreign_key_name} with
            // the value of {$this->_table}.{$foreign_key_name_in_current_models_table} .
            $wherevalue = $this->$foreignkeynameincurrentmodelstable;
        }
        return static::factory($associatedclassname, $connectionname)->where($foreignkeyname, $wherevalue);
    }

    /**
     * Overrided
     *
     * @param  string      $associatedclassname
     * @param  null|string $foreignkeyname
     * @param  null|string $foreignkeynameinassociatedmodelstable
     * @param  null|string $connectionname
     * @return $this|null
     */
    protected function belongs_to($associatedclassname, $foreignkeyname = null,
                                  $foreignkeynameinassociatedmodelstable = null, $connectionname = null) {
        $associatedtablename = self::_get_table_name(self::$auto_prefix_models . $associatedclassname);
        $foreignkeyname = self::_build_foreign_key_name($foreignkeyname, $associatedtablename);
        $associatedobjectid = $this->$foreignkeyname;

        $desiredrecord = null;
        if (is_null($foreignkeynameinassociatedmodelstable) ) {
            // E.g. "{$associated_table_name}.primary_key = {$associated_object_id}".
            // NOTE: primary_key is a placeholder for the actual primary key column's name in $associated_table_name.
            $desiredrecord = static::factory($associatedclassname, $connectionname)->where_id_is($associatedobjectid);
        } else {
            // E.g. " {$associated_table_name}.{$foreign_key_name_in_associated_models_table} = {$associated_object_id}".
            $desiredrecord = static::factory($associatedclassname, $connectionname)
                ->where($foreignkeynameinassociatedmodelstable, $associatedobjectid);
        }
        return $desiredrecord;
    }

}
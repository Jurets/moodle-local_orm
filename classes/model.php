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
        if(function_exists('get_called_class')) {
            $model = static::factory(get_called_class());
            return call_user_func_array(array($model, $method), $parameters);
        }
    }

    /**
     * Overrided
     *
     * @param string $class_name
     * @param null $connection_name
     * @return wrapper
     */
    public static function factory($class_name, $connection_name = null) {
        $table_name = static::_get_table_name($class_name);

        $parsed = explode('\\', $class_name);
        if (count($parsed) == 1) {
            //$class_name = '\local_orm\\' . $class_name;
            $namespace = $parsed = explode('\\', get_called_class());
            array_pop($namespace);
            $namespace = implode('\\', $namespace);
            $class_name = "\\$namespace\\$class_name";
        }
        //$class_name = static::$auto_prefix_models . $class_name;

        if ($connection_name == null) {
            $connection_name = static::_get_static_property(
                $class_name,
                '_connection_name',
                wrapper::DEFAULT_CONNECTION
            );
        }
        $table_name = static::table_prefix($table_name);
        $wrapper = wrapper::for_table($table_name, $connection_name);
        $wrapper->set_class_name($class_name);
        $wrapper->use_id_column(static::_get_id_column_name($class_name));
        return $wrapper;
    }

    /**
     * Overrided
     *
     * @param  string      $associated_class_name
     * @param  null|string $foreign_key_name
     * @param  null|string $foreign_key_name_in_current_models_table
     * @param  null|string $connection_name
     * @return ORMWrapper
     */
    protected function _has_one_or_many($associated_class_name, $foreign_key_name=null, $foreign_key_name_in_current_models_table=null, $connection_name=null) {
        $base_table_name = self::_get_table_name(get_class($this));
        $foreign_key_name = self::_build_foreign_key_name($foreign_key_name, $base_table_name);

        $where_value = ''; //Value of foreign_table.{$foreign_key_name} we're
        //looking for. Where foreign_table is the actual
        //database table in the associated model.

        if(is_null($foreign_key_name_in_current_models_table)) {
            //Match foreign_table.{$foreign_key_name} with the value of
            //{$this->_table}.{$this->id()}
            $where_value = $this->id();
        } else {
            //Match foreign_table.{$foreign_key_name} with the value of
            //{$this->_table}.{$foreign_key_name_in_current_models_table}
            $where_value = $this->$foreign_key_name_in_current_models_table;
        }
        return static::factory($associated_class_name, $connection_name)->where($foreign_key_name, $where_value);
    }

    /**
     * Overrided
     *
     * @param  string      $associated_class_name
     * @param  null|string $foreign_key_name
     * @param  null|string $foreign_key_name_in_associated_models_table
     * @param  null|string $connection_name
     * @return $this|null
     */
    protected function belongs_to($associated_class_name, $foreign_key_name=null, $foreign_key_name_in_associated_models_table=null, $connection_name=null) {
        $associated_table_name = self::_get_table_name(self::$auto_prefix_models . $associated_class_name);
        $foreign_key_name = self::_build_foreign_key_name($foreign_key_name, $associated_table_name);
        $associated_object_id = $this->$foreign_key_name;

        $desired_record = null;
        if( is_null($foreign_key_name_in_associated_models_table) ) {
            //"{$associated_table_name}.primary_key = {$associated_object_id}"
            //NOTE: primary_key is a placeholder for the actual primary key column's name
            //in $associated_table_name
            $desired_record = static::factory($associated_class_name, $connection_name)->where_id_is($associated_object_id);
        } else {
            //"{$associated_table_name}.{$foreign_key_name_in_associated_models_table} = {$associated_object_id}"
            $desired_record = static::factory($associated_class_name, $connection_name)->where($foreign_key_name_in_associated_models_table, $associated_object_id);
        }
        return $desired_record;
    }

}
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
 * Adapter for original vendor paris bootstrap
 * - use phpunitvendor.xml to 'wrap' instead original bootstrap file
 *
 * @package    local_orm
 * @copyright  2019 Jurets
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Code below is needed to adapt ancestors of oldscholl class PHPUnit_Framework_TestCase!
require_once(dirname(__FILE__) . '/vendor/j4mie/idiorm/test/PHPUnit_Framework_TestCase.php');

// Include original paris bootstrap file.
require_once(dirname(__FILE__) . '/vendor/j4mie/paris/test/bootstrap.php');

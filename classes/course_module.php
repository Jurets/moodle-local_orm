<?php
/**
 * Created by PhpStorm.
 * User: jurets
 * Date: 1/3/2019
 * Time: 18:10
 */

namespace local_orm;


class course_module extends model {

    public static $_table = 'course_modules';

    public function module() {
        return $this->belongs_to('modules', 'module', 'id');
        //return $this->has_one('modules', '');
    }

}
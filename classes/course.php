<?php
/**
 * Created by PhpStorm.
 * User: jurets
 * Date: 1/3/2019
 * Time: 18:10
 */

namespace local_orm;


class course extends model {

    public function modules() {
        return $this->has_many('course_module');
    }

}
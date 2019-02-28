<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/8/3
 * Time: 9:35
 */

namespace app\api\model;


class DMB
{
    private $offset = 1;
    private $limit = 10;
    private $status = 0;

    public function setOffset($offset = 1) {
        $this->offset = $offset;
    }

    public function getOffset() {
        return $this->offset;
    }

    public function setLimit($limit = 10) {
        $this->limit = $limit;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function setStatus($status = 0) {
        $this->status = $status;
    }

    public function getStauts() {
        return $this->status;
    }
}
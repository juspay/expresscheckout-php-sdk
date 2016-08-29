<?php

namespace Juspay\Model;

abstract class JuspayEntityList {
    public $list;
    public $count;
    public $offset;
    public $total;
    public function __construct($params) {
        $this->count = $params ["count"];
        $this->offset = $params ["offset"];
        $this->total = $params ["total"];
        $this->list = array ();
    }
}

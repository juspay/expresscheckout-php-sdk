<?php

namespace Juspay\Model;

class CustomerList extends JuspayEntityList {
	
	public function __construct($params) {
		parent::__construct($params);
	    for ($i=0; $i<sizeof($params["list"]); $i++) {
    		$this->list[$i] = new Customer($params["list"][$i]);
    	}
	}
	
}

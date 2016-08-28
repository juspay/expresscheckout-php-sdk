<?php

namespace Juspay\Model;

class WalletList extends JuspayEntityList {
	
	public function __construct($params) {
		parent::__construct($params);
	    for ($i=0; $i<sizeof($params["list"]); $i++) {
    		$this->list[$i] = new Wallet($params["list"][$i]);
    	}
	}
	
}

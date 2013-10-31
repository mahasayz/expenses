<?php

namespace Expense\StoreBundle\POJO;

class GraphData implements \JsonSerializable {
	protected $period;
	protected $amount;
	protected $type;
	public function getAmount() {
		return $this->amount;
	}
	public function getPeriod() {
		return $this->period;
	}
	public function getType(){
		return $this->type;		
	}
	public function setAmount($amount) {
		$this->amount = $amount;
	}
	public function setPeriod($period) {
		$this->period = $period;
	}
	public function setType($type){
		$this->type = $type;
	}
	function jsonSerialize() {
		return array (
				'period' => $this->period,
				'amount' => $this->amount 
		);
	}
}

?>
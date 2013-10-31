<?php
	class Rec implements JsonSerializable{
		protected $period;
		protected $amount;
		public function getAmount(){
			return $this->amount;
		} 
		public function getPeriod(){
			return $this->period;
		}
		public function setAmount($amount){
			$this->amount = $amount;
		}
		public function setPeriod($period){
			$this->period=$period;
		}
		function jsonSerialize(){
			return array('period'=>$this->period, 'amount'=>$this->amount);
		}
	}
?>
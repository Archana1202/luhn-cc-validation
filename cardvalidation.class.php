<?php 

class LuhnCardValidation {
	public $valid = false;
	public $valid_cc;
	public $valid_cc_type_pair;
	public $valid_ccv_type_pair;
	public $error;

	public function validate($cc, $type = false, $ccv = false) {
		if($cc !== false) {
			$this->valid_cc = $this->luhn_algorithm($cc);
			if($this->valid_cc === true) {
				$this->valid = true;
				if($type !== false) {
					$this->valid_cc_type_pair = $this->cc_type_pair($cc, $type);
					($this->valid_cc_type_pair === true) ?  $this->valid = true : $this->valid = false;
				}
				if($ccv !== false) {
					$this->valid_ccv_type_pair = $this->ccv_type_pair($ccv, $type);
					($this->valid_ccv_type_pair === true) ?  $this->valid = true : $this->valid = false;
				}
				return $this->valid;
			}
			return false;
		} else {
			$this->error = '';
		}
	}

	private function luhn_algorithm($cc) {
		if($cc) {
			$c = 1;
			for($i = strlen($cc); $i > 0; $i--) {
				$d = (int)substr($cc, $i -1, 1);
				if($c % 2 == 0) $d = $d * 2;
				if($d > 9) $d = (int)substr($d, 0, 1) + (int)substr($d, 1, 1);
				$t = $t + $d;
				$c++;
			}
			return ($t % 10 == 0) ? true : false;
		} else {
			$this->error = 'A valid card number is required to run this operation.';
		}
		return false;
	}

	private function cc_type_pair($cc, $type) {
		if(isset($cc) && isset($type)) {
			$cards = array(
			'VISA' => '/^4\d{12}(\d{3})?$/',
			'MASTERCARD' => '/^5[1-5]\d{14}$/',
			'DISCOVER' => '/^6011\d{14}$/',
			'AMEX' => '/^3(4|7)\d{13}$/',
			'SOLO' => '/^6767\d{12}(\d{2,3})?$/',
			'MAESTRO' => '/^(5020|5038|6304|6759|6761)\d{12}(\d{2,3})?$/'
			);
			$type = strtoupper($type);
			if(isset($cards[$type])){
				if(preg_match($cards[$type], $cc)){
					return true;
				}
			}
		} else {
			$this->error = 'A valid card number and type is required to run this operation.';
		}
		return false;
	}

	private function ccv_type_pair($ccv, $type) {
		if(isset($ccv) && isset($type)) {
			$cards = array(
			'VISA' => 3,
			'MASTERCARD' => 3,
			'DISCOVER' => 3,
			'AMEX' => 4,
			'SOLO' => 3,
			'MAESTRO' => 3
			);
			$type = strtoupper($type);
			if(isset($cards[$type])){
				if(strlen($ccv) == $cards[$type]){
					return true;
				}
			}
		} else {
			$this->error = 'A valid card security code and type is required to run this operation.';
		}
		return false;
	}
}

?>
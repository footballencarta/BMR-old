<?php
class BMR {
	
	public $Gender,$Age,$Height,$Weight,$BMR,$ErrorMsg;
	public $HasCalculatedBMR = false;
	public $Error = false;		
	
	// set age
	public function SetAge($age) {
		if (!is_int($age)||$age<18) {
			$this->SetError('Invalid age');		
			return false;
		}
		$this->Age=$age;
		return true;
	}
	
	// set gender
	public function SetGender($gender) {
		$gender=strtolower($gender);
		if ($gender=='m'||$gender=='male') {
			$this->Gender='m';
			return true;	
		}
		elseif ($gender=='f'||$gender=='female') {
			$this->Gender='f';
			return true;	
		}
		else {
			$this->SetError('Invalid gender');
			return false;
		}
	}	
	
	// set height 
	// end result is always in CM
	public function SetHeight($height) {		
		// $height = cm or feet and inches (comma separated)
		// i.e 170 or "5,7"
				
		if (strpos($height,',')===true) {
			/**
			 * measurement is feet and inches
			 * $height must be feet,inch format
			 * i.e for 5 foot 7 inches: 5,7
			 * i.e for 5 foot eactly: 5,0
			 */
			echo 1;
			// define $heights array
			// add $height values into it
			// [0] = feet, [1] = inches
			// i.e 5'7 = array(0=>5,1=>7)
			$heights=[];
			if (strpos($height,',')===true) {
				$heights=explode(',',$height);
				if (count($heights)!=2) {
					$this->SetError('Invalid height given for feet option');
					return false;
				}
			}
			else {
				$heights[]=$height;
			}
			
			// convert to int
			foreach($heights as $k=>$v){
				$heights[$k]=intval($v);
			}
			
			// define total inches
			$total_inches=0;
			
			// validate feet and add to $total_inches
			if (!$heights[0]||$heights[0]<3||$heights[0]>9) {
				$this->SetError('Invalid height given for feet option');
				return false;	
			}
			$total_inches=$heights[0];
			
			// validate inches and add to $total_inches
			if ($heights[1]) {
				if ($heights[1]<0||$heights[1]>11) {
					$this->SetError('Invalid height given for feet option');	
					return false;	
				}
				$total_inches=$total_inches+$heights[0];
			}
			
			// convert to cm
			if ($total_inches<33||108) {
				$this->SetError('Invalid calculation for feet option');	
				return false;		
			}
			
			// convert inches to cm
			$cm = $total_inches * 2.54; 
				
		}	
		else {
			// if $measurement defined it can only be cm
			if ($measurement&&$measurement!='cm') {
				$this->SetError('Invalid measurement');
				return false;
			}
			// its CM already 
			$cm = $height;
		}		
		
		if ($cm<119||$cm>274) {
			$this->SetError('Invalid height - out of range');
			return false;	
		}
		
		$this->Height=$cm;
		return true;		
	}
	
	// calculate BMR
	public function CalculateBMR() {
			
	}
	
	// get BMR 
	public function GetBMR($options) {
		// if you pass refresh in $options it will calculate first
		if ($options['refresh']) {
			if (!$this->Calculate()){
				return $ErrorMsg;
			}
		}	
		return $this->BMR;
	}
	
	// define error and error message
	public function SetError($msg) {
		$this->Error=true;
		if (!$msg) {
			$msg = 'Unknown error';
		}
		$this->ErrorMsg=$msg;	
		return true;
	}
			
}

$BMR = new BMR;
$BMR->SetHeight('5,7');
print_r($BMR);
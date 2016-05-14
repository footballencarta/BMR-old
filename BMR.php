<?php
/**
 * Calculars a persons BMR (Basal Metabolic Rate)
 */

class BMR
{

    public $Gender, $Age, $Height, $Weight, $BMR, $ErrorMsg;
    public $HasCalculatedBMR = false;
    public $Error            = false;

    public function __construct($options)
    {
        if ($options) {
            $this->SetOptions($options);
        }
    }

    public function SetOptions($options)
    {
        if ($options['height']) {
            $this->SetHeight($options['height']);
        }
        if ($options['weight']) {
            $this->SetWeight($options['weight']);
        }
        if ($options['weight_lbs']) {
            $this->SetWeight($options['weight_lbs'], 'lbs');
        }
        if ($options['age']) {
            $this->SetAge($options['age']);
        }
        if ($options['gender']) {
            $this->SetGender($options['gender']);
        }
        return true;
    }

    // set age
    public function SetAge($age)
    {
        if (!is_int($age) || $age < 12) {
            $this->SetError('Invalid age');
            return false;
        }
        $this->Age = $age;
        return true;
    }

    // set gender
    public function SetGender($gender)
    {
        $gender = strtolower($gender);
        if ($gender == 'm' || $gender == 'male') {
            $this->Gender = 'm';
            return true;
        } elseif ($gender == 'f' || $gender == 'female') {
            $this->Gender = 'f';
            return true;
        } else {
            $this->SetError('Invalid gender');
            return false;
        }
    }

    // set height
    // end result is always in CM
    public function SetHeight($height)
    {
        // $height = cm or feet and inches (comma separated)
        // i.e 170 or "5,7"

        if (strpos($height, ",") !== false) {
            /**
             * measurement is feet and inches
             * $height must be feet,inch format
             * i.e for 5 foot 7 inches: 5,7
             * i.e for 5 foot eactly: 5,0
             */

            // define $heights array
            // add $height values into it
            // [0] = feet, [1] = inches
            // i.e 5'7 = array(0=>5,1=>7)
            $heights = explode(',', $height);
            if (count($heights) != 2) {
                $this->SetError('Invalid height given for feet option');
                return false;
            }

            // convert to int
            foreach ($heights as $k => $v) {
                $heights[$k] = intval($v);
            }

            // define total inches
            $total_inches = 0;

            // validate feet
            if (!$heights[0] || $heights[0] < 3 || $heights[0] > 9) {
                $this->SetError('Invalid height given for feet option');
                return false;
            }

            // convert feet to inches and add to $total_inches
            $total_inches = $heights[0] * 12;

            // validate inches and add to $total_inches
            if ($heights[1]) {
                if ($heights[1] < 0 || $heights[1] > 11) {
                    $this->SetError('Invalid height given for feet option');
                    return false;
                }
                $total_inches = $total_inches + $heights[1];
            }

            // convert to cm
            if ($total_inches < 33 || $total_inches > 108) {
                $this->SetError('Invalid calculation for feet option');
                return false;
            }

            // convert inches to cm
            $cm = $total_inches * 2.54;

        } else {
            // if $measurement defined it can only be cm
            if ($measurement && $measurement != 'cm') {
                $this->SetError('Invalid measurement');
                return false;
            }
            // its CM already
            $cm = $height;
        }

        if ($cm < 119 || $cm > 274) {
            $this->SetError('Invalid height - out of range');
            return false;
        }

        $this->Height = $cm;
        return true;
    }

    public function SetWeight($weight, $measurement)
    {
        // will naturally assumes KG
        // for LBS pass $measurement as 'lbs'
        if ($measurement == 'lbs') {
            // convert lbs to kg
            $weight = $weight * 0.45359237;
        }
        $this->Weight = $weight;
        return true;
    }

    // calculate BMR
    public function CalculateBMR()
    {
        // reset flags and bmr
        $this->HasCalculatedBMR = false;
        $this->BMR              = 0;

        // validate parameters
        if (!$this->Age || $this->Age < 18) {
            $this->SetError('Invalid Age');
            return false;
        }
        if (!$this->Weight) {
            $this->SetError('Invalid Weight');
            return false;
        }
        if (!$this->Height || $this->Height < 119 || $this->height > 274) {
            $this->SetError('Invalid Height');
            return false;
        }
        if ($this->Gender != 'm' && $this->Gender != 'f') {
            $this->SetError('Invalid Gender');
            return false;
        }

        // perform calculation
        if ($this->Gender == 'm') {
            // male
            $this->BMR = 66 + (13.8 * $this->Weight) + (5 * $this->Height) - (6.8 * $this->Age);
        } else {
            // female
            $this->BMR = 655 + (9.6 * $this->Weight) + (1.8 * $this->Height) - (4.7 * $this->Age);
        }

        $this->HasCalculatedBMR = true;
        return true;

    }

    // get BMR
    public function GetBMR($options)
    {
        // return BMR fixed to two decimals
        // if you pass refresh in $options it will calculate first
        if ($this->HasCalculatedBMR === false || $options['refresh']) {
            if (!$this->CalculateBMR()) {
                return $ErrorMsg;
            }
        }
        return number_format($this->BMR, 2, '.', '');
    }

    // define error and error message
    public function SetError($msg)
    {
        $this->Error = true;
        if (!$msg) {
            $msg = 'Unknown error';
        }
        $this->ErrorMsg = $msg;
        return true;
    }

}

/**

Example Usage:

$options = [
	'height'=>'5,7',
	'weight_lbs'=>200,
	'age'=>28,
	'gender'=>'m'
];

$BMR = new BMR($options);

if ($BMR->CalculateBMR()) {
	echo $BMR->GetBMR();
}
else {
	echo $BMR->ErrorMsg;
}
 */
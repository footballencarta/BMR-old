# BMR 
PHP class to calculate a persons "Basal Metabolic Rate". Supports both imperial and metric measurements.

# Usage:

### include class
```
include_once "BMR.php";
```

### Define Options
```
$options = [
	'height'=>'5,7',
	'weight_lbs'=>160,
	'age'=>28,
	'gender'=>'m'
];
```
### Get the BMR

```
$BMR = new BMR($options);
echo $BMR->GetBMR();
```



# Options

### height
For CM (Centimeters) pass the value `"height"->150`

For feet & inches separate by comma `"height"=>"5,7"` or `"height"=>"6,3"`

### weight
KG (Kilograms): use key "weight" and pass the value `"weight"->80`

LBS (pounds): use key "weight_lbs" and pass the value `"weight_lbs"->176`

### gender
accepted values (case-insensitive): `m` `male` `f` `female`

### age
pass the value `"age"=>28`



# Setting values manually
you may set the values using the following

### height
`$BMR->SetHeight(170);` or `$BMR->SetHeight('5,7');`

### weight
`$BMR->SetWeight(80);` or `$BMR->SetWeight(160,'lbs');`

### gender
`$BMR->SetGender('male');` or `$BMR->SetGender('female');`

### age
`$BMR->SetAge(28);`

### example using these setters
```
$BMR = new BMR;
$BMR->SetHeight(170);
$BMR->SetWeight(80);
$BMR->SetGender('male');
$BMR->SetAge(28);
echo $BMR->GetBMR();
```

### calculate function
you may use `$BMR->CalculateBMR()` to perform the calculation, which will return `true` or `false`.

if this returns `false` you may locate the error mesage with `echo $BMR->ErrorMsg`


# Error Messages

every function except GetBMR() will store an error message if false

## error message examples
```
if (!$BMR->SetHeight('some incorrect value')){
  echo $BMR->ErrorMsg; // returns: Invalid height - out of range
}

if (!$BMR->CalculateBMR()){
  echo $BMR->ErrorMsg; 
}

```

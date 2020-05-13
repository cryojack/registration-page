<?php
$username = "Maximus";
echo $username;
$current = $username;
echo "<br>";
echo $current;
echo "<br>";
$current = 15;
echo "current is now " . $current;
$i = 0;
$j = 10;
echo "<br>";
if($i-- == 0){
	echo $i;
}
echo "<br>";

$author = "Brian W. Kernighan";
echo <<<_END
Debugging is twice as hard as writing the code in the first place.
Therefore, if you write the code as cleverly as possible, you are,
by definition, not smart enough to debug it.
- $author.
_END;
echo "<br>";
$number = 12345 * 67890;
echo substr($number, 3, 4);
echo "<br>";
echo "This is line " . __LINE__ . " of file " . __FILE__;
echo "<br>";
function longdate($timestamp)
{
return date("l jS", $timestamp);
}
echo longdate(time());
echo "<br>";
$a = "1000";
$b = "+1000";
if ($a == $b) echo "1";
if ($a === $b) echo "2";
echo "<br>";
$thisnum = 0;
while($thisnum < 10)
{
	$thisnum += 1;
	echo "This nums is now " . $thisnum;
	echo "<br>";
}
echo "<br>";
$counter = 1;
while($counter <= 10)
{
	echo $counter . " raised to 2 = " . $counter * $counter ."<br>";
	$counter++;
}

echo "<br>";
$a = 11;
$b = 27;
echo $a/$b . " -- " . (int)($a/$b);
echo "<br>";
echo "<br>";

$name_array = array("MriDul","mUraLeEdhaRAN","SandY","DesIRE","tACO","fRIdaY");
echo fix_name($name_array);
function fix_name(&$arr)
{
	$counter1 = 0;
	$arrcount = count($arr);
	while($counter1 < $arrcount){
		echo ucfirst(strtolower($arr[$counter1]));
		echo "<br>";
		$counter1 += 1;
	}
}

echo "<br>";
echo "<br>";

if (function_exists("fix_name"))
{
	echo "It's there";
}
echo "<br>";
echo phpversion();
echo "<br>";

class Person
{
	public $name, $age, $gender;

	function display_person(){
		echo "Name = ".$name;
		echo "<br>";
		echo "Age = ".$age;
		echo "<br>";
		echo "Gender = ".$gender;
		echo "<br>";
	}
}
$person = new Person;
$person->name = "Mridul";
$person->age = 28;
$person->gender = "Male";
echo "<br>";
print_r($person);
echo "<br>";


?>
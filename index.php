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
?>
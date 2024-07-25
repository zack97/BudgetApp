<?php

// Function to generate a random string of given length
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Generate and print a random string of length 15
$randomString = generateRandomString(15);
echo "Random String: " . $randomString . "\n";

// Create an array of random numbers
$randomNumbers = [];
for ($i = 0; $i < 10; $i++) {
    $randomNumbers[] = rand(1, 100);
}

// Print the random numbers
echo "Random Numbers: " . implode(", ", $randomNumbers) . "\n";

// Calculate and print the sum of the random numbers
$sum = array_sum($randomNumbers);
echo "Sum of Random Numbers: " . $sum . "\n";

// Function to check if a number is prime
function isPrime($num) {
    if ($num <= 1) {
        return false;
    }
    for ($i = 2; $i <= sqrt($num); $i++) {
        if ($num % $i == 0) {
            return false;
        }
    }
    return true;
}

// Check and print which numbers are prime
$primeNumbers = array_filter($randomNumbers, 'isPrime');
echo "Prime Numbers: " . implode(", ", $primeNumbers) . "\n";

// Create an associative array of students and their grades
$students = [
    "Alice" => rand(50, 100),
    "Bob" => rand(50, 100),
    "Charlie" => rand(50, 100),
    "David" => rand(50, 100),
    "Eve" => rand(50, 100)
];

// Print the students and their grades
echo "Students and Grades:\n";
foreach ($students as $name => $grade) {
    echo $name . ": " . $grade . "\n";
}

// Find and print the student with the highest grade
$topStudent = array_keys($students, max($students));
echo "Top Student: " . $topStudent[0] . " with grade " . $students[$topStudent[0]] . "\n";

// Function to reverse a string
function reverseString($str) {
    return strrev($str);
}

// Reverse and print the random string
$reversedString = reverseString($randomString);
echo "Reversed Random String: " . $reversedString . "\n";

// Convert the students' grades array to JSON and print it
$studentsJson = json_encode($students);
echo "Students JSON: " . $studentsJson . "\n";

// Decode the JSON back to an array and print it
$studentsArray = json_decode($studentsJson, true);
echo "Decoded Students Array:\n";
print_r($studentsArray);

?>


<?php
// Learn PHP Step by Step

// Section 1: Introduction to PHP
echo "<h2>1. Introduction to PHP</h2>";
echo "<p>PHP is a popular server-side scripting language designed for web development. It can be embedded into HTML and is widely used to create dynamic web pages.</p>";

// Section 2: PHP Syntax and Variables
echo "<h2>2. PHP Syntax and Variables</h2>";
$greeting = "Hello, World!";
echo $greeting . "<br>";

// Section 3: PHP Control Structures
echo "<h2>3. PHP Control Structures</h2>";
$number = 10;
if ($number > 0) {
    echo "Positive number<br>";
} else {
    echo "Negative number<br>";
}

for ($i = 0; $i < 5; $i++) {
    echo $i . "<br>";
}

switch ($number) {
    case 10:
        echo "Ten<br>";
        break;
    default:
        echo "Not ten<br>";
}

// Section 4: PHP Functions
echo "<h2>4. PHP Functions</h2>";
function add($a, $b) {
    return $a + $b;
}

$result = add(3, 4);
echo $result . "<br>"; // Outputs: 7

// Section 5: PHP Forms and User Input
echo "<h2>5. PHP Forms and User Input</h2>";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    echo "<p>Hello, $name!</p>";
}
?>
<form method="post" action="">
    <input type="text" name="name" placeholder="Enter your name">
    <input type="submit" value="Submit">
</form>
<?php

// Section 6: PHP Sessions
echo "<h2>6. PHP Sessions</h2>";
session_start();
$_SESSION["user"] = "John Doe";
echo "User is " . $_SESSION["user"] . "<br>";
session_unset();
session_destroy();

// Section 7: PHP and MySQL
echo "<h2>7. PHP and MySQL</h2>";
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["name"]. "<br>";
    }
} else {
    echo "0 results<br>";
}
$conn->close();

// Section 8: PHP Include and Require
echo "<h2>8. PHP Include and Require</h2>";
include "header.php";
require "config.php";

// Section 9: PHP Error Handling
echo "<h2>9. PHP Error Handling</h2>";
function customError($errno, $errstr) {
    echo "Error: [$errno] $errstr<br>";
}

set_error_handler("customError");

echo $test;

try {
    if(!file_exists("test.txt")) {
        throw new Exception("File not found");
    }
} catch (Exception $e) {
    echo "Message: " . $e->getMessage() . "<br>";
}

// Section 10: PHP File Handling
echo "<h2>10. PHP File Handling</h2>";
$file = fopen("test.txt", "r") or die("Unable to open file!");
echo fread($file, filesize("test.txt")) . "<br>";
fclose($file);

$file = fopen("test.txt", "w") or die("Unable to open file!");
$txt = "Hello, World!";
fwrite($file, $txt);
fclose($file);
?>


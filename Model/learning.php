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

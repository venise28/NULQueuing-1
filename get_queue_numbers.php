<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "queuing_system";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define an array to store queue numbers
$queueNumbers = [];

// Define an array of office names and their corresponding prefixes
$offices = [
    'academics' => 'ACADEMICS',
    'assets' => 'ASSETS',
    "admission" => "ADMISSION",
    "registrar" => "REGISTRAR",
    "accounting" => "ACCOUNTING",
    "clinic" => "CLINIC",
    "itro" => "ITRO",
    "guidance" => "GUIDANCE",
];

// Fetch real-time queue numbers for each office
foreach ($offices as $key => $officeName) {
    // Add code to query the database and fetch the real-time queue number for the current office
    $sql = "SELECT queue_number FROM queue WHERE office = '$officeName' ORDER BY timestamp DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $queueNumber = $row['queue_number'];
        $queueNumbers[$key] = $queueNumber;
    } else {
        // If no records exist for an office, set the queue number to "N/A" or another appropriate value
        $queueNumbers[$key] = "N/A";
    }
}

// Return the queue numbers as JSON
header('Content-Type: application/json');
echo json_encode($queueNumbers);

$conn->close();
?>


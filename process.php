<?php
@include 'database.php';

// Handle the request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $office = $_POST["office"];
    $studentId = $_POST["studentId"];
    $program = $_POST["program"];

    // Get the next queue number for the selected office
    $queueNumber = getNextQueueNumber($office);

    // Insert the record into the database
    $sql = "INSERT INTO queue (student_id, program, queue_number, office) VALUES ('$studentId', '$program', '$queueNumber', '$office')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "queue_number" => $queueNumber]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $sql . "<br>" . $conn->error]);
    }
} else {
    echo "Invalid request";
}


// Function to get the next queue number for a given office
function getNextQueueNumber($office) {
    global $conn;
    // Define office-specific prefixes
    $prefixes = [
        "ADMISSION" => "AD",
        "REGISTRAR" => "R",
        "ACCOUNTING" => "AC",
        "CLINIC" => "CL",
        "ASSETS" => "AS",
        "ITRO" => "IT",
        "GUIDANCE" => "G",
        "ACADEMICS" => "F",
    ];

    $prefix = $prefixes[$office];

    $sql = "SELECT MAX(queue_number) as max_queue FROM queue WHERE office = '$office'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $maxQueue = $row['max_queue'];
        // Extract the numeric part of the queue number
        $numericPart = (int)substr($maxQueue, strlen($prefix));
        // Increment the numeric part
        $nextNumericPart = $numericPart + 1;
        // Format the next queue number
        $nextQueue = $prefix . str_pad($nextNumericPart, 3, '0', STR_PAD_LEFT);
        return $nextQueue;
    } else {
        // If no records exist for the office, start from 001
        return $prefix . "001";
    }
}

$conn->close();
?>

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

if ($_SERVER["REQUEST_METHOD"] == "GET") { 

    // Fetch data from the program_chair table
$sql = "SELECT name, program FROM program_chairs";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value=" ">' . $row["name"] . ' - ' . $row["program"] . '</option>';
    }
} else {
    echo '<option value="">No program chairs available</option>';
}


}


if ($_SERVER["REQUEST_METHOD"] == "POST") { 
// Get data from the POST request
$concern = $_POST["concern"];
$program = $_POST["program"];
$studentId = $_POST["studentId"];


$queueNumber = getNextQueueNumber($program);

// Validate and sanitize user inputs if needed

// Perform the database insertion
$sql = "INSERT INTO academics (concern, program, student_id, queue_number) VALUES ('$concern', '$program', '$studentId', '$queueNumber')";

if ($conn->query($sql) === TRUE) {
    // Insert into queue table
    $office = $_POST["office"];
    $program_queue = $_POST["program_queue"];
    $studentId = $_POST["studentId"];

    $queueSql = "INSERT INTO queue (student_id, program, queue_number, office) VALUES ('$studentId', '$program_queue', '$queueNumber', '$office')";
    if ($conn->query($queueSql) === TRUE) {
        echo json_encode(["success" => true, "queue_number" => $queueNumber]);
    } else {
        echo json_encode(["success" => false, "message" => "Error inserting into queue table: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Error inserting into academics table: " . $conn->error]);
}
} else {
echo "Invalid request";
}



function getNextQueueNumber($program) {
    global $conn;
    // Define office-specific prefixes
    $prefixes = [
        "SCS" => "SCS",
        "SAS" => "SAS",
        "SEA" => "SEA",
        "SABM" => "SAB",
        "SHS" => "SHS",
    ];

    $prefix = $prefixes[$program];

    $sql = "SELECT MAX(queue_number) as max_queue FROM academics WHERE program = '$program'";
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

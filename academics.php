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
    if (isset($_GET['program'])) {
        $program = $_GET['program'];
        $query = "SELECT id, name FROM program_chairs WHERE program = '$program'";
        $result = mysqli_query($conn, $query);

        $data = array();

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[$row['id']] = $row['name'];
            }
        }

        echo json_encode($data);
    } else {
        echo json_encode(array()); 
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the POST request
    $concern = $_POST["concern"];
    $program = $_POST["program"];
    $studentId = $_POST["studentId"];

    $queueNumber = getNextQueueNumber($program);


    // database insert
    $sql = "INSERT INTO academics (concern, program, student_id, queue_number) VALUES ('$concern', '$program', '$studentId', '$queueNumber')";

    if ($conn->query($sql) === TRUE) {
        // Insert into queue table
        $office = $_POST["office"];
        $program_queue = $_POST["program_queue"];
        $studentId = $_POST["studentId"];

        // Check $office and set it to "ACADEMICS" if true
        $allowedOffices = ["SCS", "SEA", "SAS", "SABM", "SHS"];
        if (in_array($office, $allowedOffices)) {
            $office = "ACADEMICS";
        }

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

        $numericPart = (int)substr($maxQueue, strlen($prefix));

        $nextNumericPart = $numericPart + 1;

        $nextQueue = $prefix . str_pad($nextNumericPart, 3, '0', STR_PAD_LEFT);
        return $nextQueue;
    } else {

        return $prefix . "001";
    }
}

$conn->close();
?>

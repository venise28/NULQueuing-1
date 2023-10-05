//local storage / validate
function submitStudentId() {
    var studentId = document.getElementById("studentId").value;
    var program = document.getElementById("program").value;

    if (studentId === "") {
        document.getElementById("error-message").style.display = "block";
        return;
    }

    // Set the student ID in local storage
    localStorage.setItem("studentId", studentId);
    localStorage.setItem("program", program);

    if (studentId === "") {
        return;
    }

    window.location.href = "nulqueue.html";
}


// queue student
function registerStudent() {
    var studentId = localStorage.getItem("studentId");
    var program = localStorage.getItem("program");
    var office = document.getElementById("modalTitle1").innerText;

    $.ajax({
        type: "POST",
        url: "process.php",
        data: { studentId: studentId, program: program, office: office },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                var queueNumber = response.queue_number;
                document.getElementById("queueNumber").innerText = queueNumber;
                $('#thirdModal').modal('show');
            } else {
                alert("Error: " + response.message);
            }
        },
        error: function () {
            alert("An error occurred.");
        }
    });
}

// queue student
function registerGuest() {
    localStorage.setItem("studentId", "GUEST");
    localStorage.setItem("program", "-");
    var studentId = localStorage.getItem("studentId");
    var program = localStorage.getItem("program");
    var office = document.getElementById("modalTitle1").innerText;

    $.ajax({
        type: "POST",
        url: "process.php",
        data: { 
            studentId: studentId, 
            program: program, 
            office: office 
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                var queueNumber = response.queue_number;
                // Set the queue number in the modal
                document.getElementById("queueNumber").innerText = queueNumber;
                // Show the third modal
                $('#thirdModal').modal('show');
            } else {
                alert("Error: " + response.message);
            }
        },
        error: function () {
            alert("An error occurred.");
        }
    });
}

// Function to update the modal titles
function updateModalTitle(modalId, title) {
    $(modalId).find(".modal-title").text(title);
}

// Event listener for button clicks

$(".btn").click(function() {
    var modalTitle = $(this).data("title");
    

    // Update modals
    updateModalTitle("#firstModal", modalTitle);
    updateModalTitle("#secondModal", modalTitle);
    updateModalTitle("#thirdModal", modalTitle);
    updateModalTitle("#acadModal", modalTitle);
    updateModalTitle("#acadModal2", modalTitle);
    updateModalTitle("#acadModal3", modalTitle);
});


// DONE EVENT LISTENER
document.querySelector("#btn-back").addEventListener("click", function () {
    // Clear storage
    localStorage.removeItem("studentId");
    localStorage.removeItem("program");
    
    window.location.href = "index.html";
});


// Function to populate the select dropdown
function populateProgramChairs() {
    // fetch data
    $.ajax({
        url: "academics.php",
        type: "GET",
        success: function (data) {
            $("#program-chair-select").append(data);
        }
    });

    $("#done-button").click(function () {

        var selectedOption = $("#program-chair-select option:selected");
        var name = selectedOption.text().split(" - ")[0];
        $("#selectedOptionValue").text(name);


    });

    // Handle the submit button click event
    $("#submit-button").click(function () {
        var studentId = localStorage.getItem("studentId");
        var selectedOption = $("#program-chair-select option:selected");

        var name = selectedOption.text().split(" - ")[0];
        var program = selectedOption.text().split(" - ")[1];
        var program_queue = localStorage.getItem("program");
        var office = document.getElementById("modalTitle1").innerText;
        

        // Send data to the server to insert into the 'academics' table
        $.ajax({
            url: "academics.php",
            type: "POST",
            data: {
                concern: name,
                program: program,
                studentId: studentId,
                office: office,
                program_queue: program_queue,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var queueNumber = response.queue_number;
                    document.getElementById("queueNumber").innerText = queueNumber;
                    $('#acadModal3').modal('show');
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function () {
                alert("An error occurred.");
            }
        });
    });
}


$(document).ready(function () {
    populateProgramChairs();
});
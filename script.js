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

// queue student
function registerGuest() {
    localStorage.setItem("studentId", "GUEST");
    localStorage.setItem("program", "GUEST");
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
    

    // Update the titles of all modals, including admissionModal
    updateModalTitle("#firstModal", modalTitle);
    updateModalTitle("#secondModal", modalTitle);
    updateModalTitle("#thirdModal", modalTitle);
    updateModalTitle("#admissionModal", modalTitle);
});


// DONE EVENT LISTENER
document.querySelector("#thirdModal .btn-yes").addEventListener("click", function () {
    // Clear storage
    localStorage.removeItem("studentId");
    
    window.location.href = "index.html";
});

$(document).ready(function () {
    // Function to update queue numbers on the page
    function updateQueueNumbers() {
        $.ajax({
            type: "GET",
            url: "get_queue_numbers.php",
            dataType: "json",
            success: function (data) {
                // Update HTML elements with the fetched queue numbers
                $("#registrarQueue").text(data.registrar);
                $("#admissionQueue").text(data.admission);
                $("#accountingQueue").text(data.accounting);
                $("#academicsQueue").text(data.academics);
                $("#clinicQueue").text(data.clinic);
                $("#assetsQueue").text(data.assets);
                $("#guidanceQueue").text(data.guidance);
                $("#itroQueue").text(data.itro);
            },
            error: function () {
                console.error("An error occurred while fetching queue numbers.");
            }
        });
    }

    // Call the updateQueueNumbers function initially and then at regular intervals
    updateQueueNumbers();
    setInterval(updateQueueNumbers, 5000); // Update every 5 seconds
});

// For DATE AND TIME
function updateTime() {
    const dateElement = document.getElementById('date');
    const timeElement = document.getElementById('time');

    const now = new Date();

    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    const formattedDate = now.toLocaleDateString('en-US', options).toUpperCase();

    const hours = now.getHours();
    const formattedHours = hours >= 12 ? hours % 12 : hours; // Convert to 12-hour format

    // Ensure that 0 is displayed as 12 for midnight
    const displayHours = formattedHours === 0 ? 12 : formattedHours;

    const minutes = now.getMinutes(); // Get the minutes
    const ampm = hours >= 12 ? 'pm' : 'am';
    const formattedTime = `${displayHours}:${minutes.toString().padStart(2, '0')}${ampm}`;

    // Update the elements with the formatted date and time
    dateElement.textContent = formattedDate;
    timeElement.textContent = formattedTime;
}

// Update the time immediately and then every second
updateTime();
setInterval(updateTime, 1000);


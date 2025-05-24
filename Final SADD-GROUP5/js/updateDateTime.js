function updateDateTime() {
    var now = new Date();
    var dayAbbreviation = now.toLocaleDateString('en-US', { weekday: 'short' }).substring(0, 3);
    var dateString = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    var timeString = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });

    var dateTimeString = dayAbbreviation + ', ' + dateString + ', ' + timeString;

    document.getElementById('dateTime').innerHTML = dateTimeString;

    // Update every second
    setTimeout(updateDateTime, 1000);
}

updateDateTime();

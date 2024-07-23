document.addEventListener('DOMContentLoaded', function() {
    const saveButton = document.getElementById('saveTaskButton');
    const startTimeInput = document.getElementById('startTime');
    const endTimeInput = document.getElementById('endTime');
    const userIdInput = document.getElementById('userId');

    saveButton.addEventListener('click', function() {
        const userId = userIdInput.value;
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;

        fetch('https://yourdomain.com/widget/index.php', {
            method: 'POST',
            body: JSON.stringify({
                user_id: userId,
                start_time: startTime,
                end_time: endTime
            }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.is_taken) {
                alert('This time slot is already taken. Please choose another time.');
            } else {
                // Proceed with saving the task
            }
        });
    });
});

<script>
    function adddays(tanggal, tempo) {
        // Split the tanggal string to get day, month, and year
        var parts = tanggal.split('-');
        var day = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10) - 1; // Months are zero-based in JavaScript
        var year = parseInt(parts[2], 10);

        // Create a Date object
        var date = new Date(year, month, day);

        // Add the tempo days
        date.setDate(date.getDate() + tempo);

        // Get the new day, month, and year components
        var newDay = ("0" + date.getDate()).slice(-2);
        var newMonth = ("0" + (date.getMonth() + 1)).slice(-2); // Months are zero-based
        var newYear = date.getFullYear();

        // Format the new date to DD-MM-YYYY
        var formattedDate = newDay + '-' + newMonth + '-' + newYear;
        // console.log(formattedDate); // Output: 31-12-2024
        return formattedDate;
    }
</script>

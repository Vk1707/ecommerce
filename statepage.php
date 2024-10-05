<?php
// Database connection
$conn = new PDO("mysql:host=localhost;dbname=test", "root", "");

// Fetch all states
$stmt = $conn->prepare("SELECT * FROM states");
$stmt->execute();
$states = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Suggestion by State</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Select State and Get City Suggestions</h2>
    <form id="stateCityForm">
        <div class="mb-3">
            <label for="state" class="form-label">Select State</label>
            <select id="state" class="form-select" name="state_id" required>
                <option value="">Select a state</option>
                <?php foreach ($states as $state): ?>
                    <option value="<?= $state['id'] ?>"><?= $state['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="city" class="form-label">City</label>
            <input type="text" id="city" class="form-control" placeholder="Start typing the city name..." required>
            <input type="hidden" id="city_id" name="city_id" required>
            <div id="cityList" class="list-group"></div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script>
$(document).ready(function () {
    $('#city').on('keyup', function () {
        var stateId = $('#state').val();
        var cityName = $(this).val();
        $('#city_id').val(''); 

        if (stateId && cityName.length > 0) {
            $.ajax({
                url: 'get_cities.php',
                method: 'POST',
                data: {state_id: stateId, city: cityName},
                success: function (data) {
                    $('#cityList').html(data);
                }
            });
        } else {
            $('#cityList').html(''); 
        }
    });

    // When a city is clicked, update the city input and city_id hidden field
    $(document).on('click', '.city-item', function () {
        var cityName = $(this).text();
        var cityId = $(this).data('id');

        $('#city').val(cityName); 
        $('#city_id').val(cityId); 
        $('#cityList').html('');
    });

    // Form validation to ensure only a clicked city can be submitted
    $('#stateCityForm').on('submit', function (e) {
        var cityId = $('#city_id').val();
        if (!cityId) {
            e.preventDefault();
            alert('Please select a city from the list.');
        }
    });
});
</script>
</body>
</html>

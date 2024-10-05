<?php
include('./inc/dbclass.php');
$db = new Database();

if (isset($_POST['state_id']) && isset($_POST['city'])) {
    $state_id = $_POST['state_id'];
    $city_name = $_POST['city'];

    // Update query to select both city_id and city
    $query = "SELECT id as city_id, city FROM cities WHERE state_id = :state_id AND city LIKE :city_name";
    $params = [
        ':state_id' => $state_id,
        ':city_name' => $city_name . '%'
    ];

    // Use your select function to fetch the data
    $cities = $db->select($query, $params);

    if ($cities) {
        foreach ($cities as $city) {
            echo '<a href="#" class="list-group-item list-group-item-action city-item" data-id="' . $city['city_id'] . '">' . $city['city'] . '</a>';
        }
    } else {
        echo '<p class="list-group-item list-group-item-action">No cities found</p>';
    }
}
?>

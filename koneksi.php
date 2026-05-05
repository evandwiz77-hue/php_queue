<?php 
    $conn = mysqli_connect("localhost", "root", "", "test_loket");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
}
?>
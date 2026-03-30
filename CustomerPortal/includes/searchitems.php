<?php
require_once("../header.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usersearch = $_POST["usersearch"];

    try {
        require_once("db.php");

        $query = "SELECT * FROM users WHERE f_name = :usersearch;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":usersearch", $usersearch);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;
        $stmt = null;
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../index.php");
}


echo "<h3>Search Results for '$usersearch':</h3>";

if (empty($results)) {
    echo "<p>No Such User!</p><br>";
} else {
    foreach ($results as $result) {
        echo "<p>First Name: {$result['f_name']} </p>";
        echo "<p>Last Name: {$result['l_name']} </p>";
        echo "<p>Phone Number: {$result['p_num']} </p>";
        echo "<p>House Number: {$result['h_num']} </p>";
        echo "<p>Password: {$result['pwd']} </p>";
        echo "<p>Account Number: {$result['acc_num']} </p>";
        echo "<p>Account Created At: {$result['created_at']} </p>";
    }
}
require_once("../footer.php");

<?php
// Include the PDO configuration file which typically contains the database connection settings.
include('config/pdoconfig.php');

// Check if the 'custName' key in the POST request is not empty.
if (!empty($_POST["custName"])) {
    // Assign the value from the POST request to the variable $id.
    $id = $_POST['custName'];
    
    // Prepare an SQL query using a named placeholder ':id' to select all fields from 'rpos_customers' where the 'customer_name' matches the provided ID.
    $stmt = $DB_con->prepare("SELECT * FROM rpos_customers WHERE customer_name = :id");
    
    // Execute the prepared statement by binding the actual value of $id to the ':id' placeholder in the SQL query.
    $stmt->execute(array(':id' => $id));

    // Start a loop to fetch each row from the result set as an associative array.
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Output the 'customer_id' from the fetched row. 'htmlentities()' is used to prevent XSS attacks by converting special characters to HTML entities.
        echo htmlentities($row['customer_id']);
    }
}
// The script ends here without closing the PHP tag, which is a common practice to prevent accidental whitespace or new lines that could cause issues.

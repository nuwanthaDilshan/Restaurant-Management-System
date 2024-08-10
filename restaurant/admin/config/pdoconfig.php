<?php
    $DB_host = "localhost";
    $DB_user = "root";
    $DB_pass = "";
    $DB_name = "restaurant";
    try
    {
        $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
        $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//controls the error mode for PDO. PDO to throw exceptions if there are errors.
    }
    catch(PDOException $e)
    {
         $e->getMessage();
    }
?>
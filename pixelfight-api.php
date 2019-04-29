<?php
include("../inc/dbinfo.inc");

if(isset($_GET["update-id"])){
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    $database = mysqli_select_db($connection, DB_DATABASE);

    $query = "SELECT * FROM `update-table`";
    $result = mysqli_query($connection,$query);
    if(!$result){
        echo("<p>Error.</p>");
        echo(mysqli_errno($connection));
    }
    else{
        $row = mysqli_fetch_row($result));
        // for each row in mysql table
        echo json_encode(array("type" => "update-id", 
                                "body" => strval($row[0])));
        
    }
}


?>
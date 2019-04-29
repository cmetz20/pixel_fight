<?php
include("../inc/dbinfo.inc");

        
// Looked up way to check if a POST has been sent to this php file
// When JSON sends a POST, get contents and then increment button DB
// function is called
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if ($contentType === "application/json") {
    $content = file_get_contents("php://input");

    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    $database = mysqli_select_db($connection, DB_DATABASE);
    $decoded_ary = explode("_", $_POST["body"]);
    
    $query = "UPDATE `button-table` SET `"  . $decoded_ary[1] ."-votes` = `"  . $decoded_ary[1] ."-votes` + 1 WHERE `point` = \"" . $decoded_ary[0] . "\";";

    $result = mysqli_query($connection,$query);
    if(!$result){
        echo("<p>Error.</p>");
        echo(mysqli_errno($connection));
    }

    $query = "UPDATE `update-table` SET `update-id` = `update-id` + 1 WHERE `update-id` = `update-id`;";
    $result = mysqli_query($connection,$query);
    if(!$result){
        echo("<p>Error.</p>");
        echo(mysqli_errno($connection));
    }
    echo echo json_encode(array("type" => "update-id", 
                                "body" => "success"));
}

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
        $row = mysqli_fetch_row($result);
        // for each row in mysql table
        echo json_encode(array("type" => "update-id", 
                                "body" => strval($row[0])));
    }
}

if(isset($_GET["board-request"])){
    /* Connect to MySQL and select the database. */
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    $database = mysqli_select_db($connection, DB_DATABASE);
    
    $query = "SELECT * FROM `button-table`;";
    $result = mysqli_query($connection,$query);
    if(!$result){
        echo("<p>Error.</p>");
        echo(mysqli_errno($connection));
    }
    else{
        $ary = array();
        $count = 0;
        while($row = mysqli_fetch_row($result)){
            // for each row in mysql table
            $count++;
            $winning_index = 2;
            $winning_votes = $row[2];
            for($i = 1; $i < 12; $i++){
                if($row[$i] > $winning_votes){
                    $winning_index = $i;
                    $winning_votes = $row[$i];
                }
            }
            $color = "white";
            switch($winning_index){
                case 1: 
                    $color = "black";
                    break;
                case 2: 
                    $color = "white";
                    break;
                case 3: 
                    $color = "gray";
                    break;
                case 4: 
                    $color = "brown";
                    break;
                case 5: 
                    $color = "red";
                    break;
                case 6: 
                    $color = "orange";
                    break;
                case 7: 
                    $color = "yellow";
                    break;
                case 8: 
                    $color = "green";
                    break;
                case 9: 
                    $color = "blue";
                    break;
                case 10: 
                    $color = "purple";
                    break;
                case 11: 
                    $color = "pink";
                    break;
                 default:
                    $color = "cyan";
                    break;
            }
            // winning color now set for current button-table`
            array_push($ary, $color);
        }
        echo json_encode($ary);
    }
}


?>
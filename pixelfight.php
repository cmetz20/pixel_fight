<!--
Christopher Metz
CSC 436 - Cloud Computing
Project 3
Pixel Fight!
-->

<?php include("../inc/dbinfo.inc");
// dbinfo.inc holds database credentials used below
// AWS tutorials helped with this secure form of storing secrets


// function is called when button is clicked.
// called when we receive a POST from javascript
// will connect to DB and increment vote for given color
// and given button
function incrementButtonDB($decoded){
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    $database = mysqli_select_db($connection, DB_DATABASE);
    $decoded_ary = explode("_", $decoded);
    
    $query = "UPDATE `button-table` SET `"  . $decoded_ary[1] ."-votes` = `"  . $decoded_ary[1] ."-votes` + 1 WHERE `point` = \"" . $decoded_ary[0] . "\";";
    ?>
    <script>
        console.log(<?php echo $query?>);
    </script>

    <?php
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
}

// Looked up way to check if a POST has been sent to this php file
// When JSON sends a POST, get contents and then increment button DB
// function is called
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if ($contentType === "application/json") {
    $content = file_get_contents("php://input");
    incrementButtonDB($content);
}

// used a template idea similar to what flask does for us
// saved a lot of HTML/PHP interweaving pains.
include("./templates/header_template.php");
include("./templates/buttons_template.php");
include("./templates/body_template.php");
include("./templates/footer_template.php");

?>

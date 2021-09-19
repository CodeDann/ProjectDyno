<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// Check if a file has been uploaded
if(isset($_FILES['uploaded_file'])) {
    // Make sure the file was sent without errors
    if($_FILES['uploaded_file']['error'] == 0) {
        // Connect to the database
        require_once "config.php";
 
        // Gather all required data
        $name = $link->real_escape_string($_FILES['uploaded_file']['name']);
        $mime = $link->real_escape_string($_FILES['uploaded_file']['type']);
        $data = $link->real_escape_string(file_get_contents($_FILES  ['uploaded_file']['tmp_name']));
        $size = intval($_FILES['uploaded_file']['size']);
 
        // Create the SQL query
        $query = "
            INSERT INTO `files` (
                `name`, `data`, `date_created`
            )
            VALUES (
                '{$name}', '{$data}', NOW()
            )";
 
        // Execute the query
        $result = $link->query($query);
 
        // Check if it was successfull
        if($result) {
            echo 'Success! Your file was successfully added!';
        }
        else {
            echo 'Error! Failed to insert the file'
               . "<pre>{$link->error}</pre>";
        }
    }
    else {
        echo 'An error accured while the file was being uploaded. '
           . 'Error code: '. intval($_FILES['uploaded_file']['error']);
    }
 
    // Close the mysql connection
    $link->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. This is the professor welcomepage.</h1>
    </div>


    <div>

        <form action="welcomeprofessor.php" method="post" enctype="multipart/form-data">
            <input type="file" name="uploaded_file">
            <input type="submit" value="Upload file">
        </form>
         <p>
            <a href="list_files.php">See all files</a>
        </p>

    </div>


    <p>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>

</body>
</html>
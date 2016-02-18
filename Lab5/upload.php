<?php
session_start();
$message = "";
$target_dir = "/var/www/html/uploaddir/".$_SESSION['email']."/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if file already exists
if (file_exists($target_file)) {
    $message = "<h4 class = \"red\">Sorry, file already exists.</h4>";
    $uploadOk = 0;
}
    
    // Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    $message = "<h4 class = \"red\">Sorry, your file is too large.</h4>";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $message .= "";
// if everything is ok, try to upload file
} else {
    $cur_loc = $_FILES["fileToUpload"]["tmp_name"];
    if (move_uploaded_file( $cur_loc, $target_file)) {
        $message = "<h4 class = \"green\">The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</h4>";
    } else {
        $message = "<h4 class = \"red\">Sorry, there was an error uploading your file.</h4>";
    }
}




 


?>


<head>
<link rel ="stylesheet" type ="text/css" href="styles.css">
<title>File Upload info</title>
</head>
<body>
    <?php echo $message; ?>
    <a href = "moreService.php">Go to services Page</a>
</body>
</html>
<?php
session_start();
$display_block = "
	<h3>Welcome ".$_SESSION['f_name']." ".$_SESSION['l_name']."!</h3>
        <p>Select either or both lotteries to play.</p>
	<form method = \"post\" action = \"lottery.php\">
        <p><label>Lotto 6/49:
	<input type = \"checkbox\" name = \"Lotto_Num\" value = \"Lotto649\">
        </label>
        <label>Lotto MAX:        
        <input type = \"checkbox\" name = \"LotMAX\" value = \"LottoMAX\">
	</label></p>
        <p>
        <input type = \"submit\" value = \"Submit\"></p></form>
        
        <form action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\">
        Select file to upload:
        <input type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\">
        <input type=\"submit\" value=\"Upload File\" name=\"submit\">
        </form>";

?>

<html>
<head>
<link rel ="stylesheet" type ="text/css" href="styles.css">
<title>More Services</title>
</head>
<body>
<?php echo "$display_block"; ?>
</body>
</html>


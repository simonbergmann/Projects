<?php
session_start();
$check = boolval(!isset($_SESSION['email']));
$check2 = boolval((!filter_input(INPUT_POST, 'username'))
        || (!filter_input(INPUT_POST, 'password')));

//check for required fields from the form
if ( (!filter_input(INPUT_POST, 'username'))
        || (!filter_input(INPUT_POST, 'password')) ) {

	header("Location: userlogin.html");
	exit;
}

//connect to server and select database
$mysqli = mysqli_connect("localhost", "cs213user", "letmein", "testDB");

//For more info about mysqli functions, go to the site below:
//http://www.w3schools.com/php/php_ref_mysqli.asp

/* create and issue the query
$sql = "SELECT f_name, l_name FROM auth_users WHERE username = '".$_POST["username"].
        "' AND password = PASSWORD('".$_POST["password"]."')";
*/

//create and issue the query
//check if user has already been logged in

$targetemail = filter_input(INPUT_POST, 'username');
$_SESSION['email'] = $targetemail;
$targetpasswd = filter_input(INPUT_POST, 'password');

$sql = "SELECT firstname, lastname FROM members WHERE email = '".$_SESSION['email'].
        "' AND password = PASSWORD('".$targetpasswd."')";

$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

//get the number of rows in the result set; should be 1 if a match
if (mysqli_num_rows($result) == 1) {

	//if authorized, get the values of f_name l_name
	while ($info = mysqli_fetch_array($result)) {
		$fname = stripslashes($info['firstname']);
                $lname = stripslashes($info['lastname']);
                $_SESSION['f_name'] = $fname;
                $_SESSION['l_name'] = $lname;
        }

	//set authorization cookie
	setcookie("auth", "1", time()+60*30, "/", "", 0);

	//create display string
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
        
} else {
	//redirect back to login form if not authorized
	header("Location: userlogin.html");
	exit;
}

?>
<html>
<head>
<link rel ="stylesheet" type ="text/css" href="styles.css">
<title>User Login</title>
</head>
<body>
<?php echo "$display_block"; ?>
</body>
</html>


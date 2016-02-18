
<?php
session_start();
$message = "";

// set variables to empty strings
$fnameErr = $lnameErr = $emailErr = $passwdErr = $ageErr = $genderErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //set the error messages for the input fields
    if (empty($_POST["firstname"])) {
        $fnameErr = "First name required";
    }
    if (empty($_POST["lastname"])) {
        $lnameErr = "Last name required";
    }
    if (empty($_POST["email"])) {
        $emailErr = "Email required";
    }
    if (empty($_POST["password"])) {
        $passwdErr = "Password required";
    }
    if (empty($_POST["age"])) {
        $ageErr = "Age required";
    }
    if (empty($_POST["gender"])) {
        $genderErr = "Gender required";
    }
}
//if all fields are entered 
if ((!empty($_POST["firstname"])) && (!empty($_POST["lastname"])) &&
        (!empty($_POST["email"])) && (!empty($_POST["password"])) &&
        (!empty($_POST["age"])) && (!empty($_POST["gender"]))) {
    // assign form input to variables used to create table
    $tgtf_name = $_POST["firstname"];
    $tgtl_name = filter_input(INPUT_POST, 'lastname');
    $tgtemail = strtolower(filter_input(INPUT_POST, 'email'));
    $tgtpasswd = filter_input(INPUT_POST, 'password');
    $tgtage = intval(filter_input(INPUT_POST, 'age'));
    $tgtgender = filter_input(INPUT_POST, 'gender');
    //$_SESSION['email'] = $tgtemail;
    //connect to server
    $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "testDB");

    $sql = "SELECT email FROM members WHERE email = '$tgtemail';";
    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
    if (mysqli_num_rows($result) == 1) {
        $message = "<p class = \"red\">The email address has already been used!<br />
            Please use a different email address for a new account.</p>";
    } else {

        // create sql query to insert form info into members table
        $sql1 = "insert into members (firstname, lastname, email, password, age, gender, startdate)
        values ('$tgtf_name', '$tgtl_name', '$tgtemail', PASSWORD('$tgtpasswd'), $tgtage, '$tgtgender', CURDATE());";
        $result1 = mysqli_query($mysqli, $sql1) or die(mysqli_error($mysqli));

        if ($result1 === TRUE) {
            $message = "<span class = \"green\">An account has been created.</span>";
            $message .= "<br><p><a href = \"userlogin.html\">Go to Login Page</a></p>";
            $path = "/var/www/html/uploaddir/".$tgtemail;
            $user = "simon";
            mkdir($path, 0733, true);
            if (mkdir === false){  
                $message .= "could not create folder";
            }
            chmod ($path, 0733);
            chown($path, $user);
            
        } else {
            $message = printf("Could not insert record: %s\n", mysqli_error($mysqli));
        }
    }
    
}
?>
<html>
    <head>
        <link rel ="stylesheet" type ="text/css" href="styles.css">
        <title>Create Account</title>
    </head>
    <body>
        <h1>New Account</h1>
        <h4> <?php echo $message; ?></h4>
        <form method="post" action="applyaccount.php">
            <p><strong>firstname:</strong><br/>
                <input type="text" name="firstname" value="<?PHP echo $_POST['firstname'];?>"/></p>
            <span class = "red"> <?php echo $fnameErr; ?></span>
            <p><strong>lastname:</strong><br/>
                <input type="text" name="lastname" value="<?PHP echo $_POST['lastname'];?>"/></p>
            <span class = "red"> <?php echo $lnameErr; ?></span>
            <p><strong>email:</strong><br/>
                <input type="email" name="email" value="<?PHP echo $_POST['email'];?>"/></p>
            <span class = "red"><?php echo $emailErr; ?></span>
            <p><strong>password:</strong><br/>
                <input type="password" name="password"/></p>
            <span class = "red"><?php echo $passwdErr; ?></span>
            <p><strong>age:</strong><br/>
                <input type="number" name="age" value="<?PHP echo $_POST['age'];?>"/></p>
            <span class = "red"><?php echo $ageErr; ?></span>
            <p><strong>gender:</strong><br/>
                <select name="gender">
                    <option value="" selected></option>
                    <option value="male">male</option>
                    <option value="female">female</option>
                </select></p>
            <span class = "red"><?php echo $genderErr; ?></span>
            <br /><input type="submit" name="submit" value="createAccount"/></p>
    </form>
</body>
</html>





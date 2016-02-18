
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" /> 
    </head>
        <?PHP
        session_start();
        include("simple-php-captcha.php");

        // If form has been submitted process it
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include("scrape_fonefinder.php");
            include("gmail.php");
            
            // global variables used throughout the script
            global $ip_address;
            global $user_agent;
            global $name;
            global $areaCode;
            global $prefix;
            global $suffix;
            global $sent_message;
            global $sms_gateways;
            global $info_message;
            global $max_num_sends;
            // constant that determines how many messages a user can send in a day
            $max_num_sends = 30;

            If (isset($_SERVER['REMOTE_ADDR'])) {
                $ip_address = $_SERVER['REMOTE_ADDR'];
            }
            If (isset($_SERVER['HTTP_USER_AGENT'])) {
                $user_agent_info = $_SERVER['HTTP_USER_AGENT'];
            }

            // define variables and set to empty string
            $name = $areaCode = $prefix = $suffix = $sent_message = $info_message = "";
            $nameErr = $areaCodeErr = $prefixErr = $suffixErr = $messageErr = "";


            /*
             * Error checking for browsers that don't support HTML5 elements
             * and sanitizing the data from the form elements
             */
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (empty($_POST["name"])) {
                    $nameErr = "Name is required";
                } else {
                    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                }

                if (empty($_POST["areaCode"])) {
                    $areaCodeErr = "Area Code is required";
                } else {
                    $areaCode = filter_input(INPUT_POST, 'areaCode', FILTER_SANITIZE_NUMBER_INT);
                }

                if (empty($_POST["prefix"])) {
                    $prefixErr = "Prefix is required";
                } else {
                    $prefix = filter_input(INPUT_POST, 'prefix', FILTER_SANITIZE_NUMBER_INT);
                }

                if (empty($_POST["suffix"])) {
                    $suffixErr = "suffix is required";
                } else {
                    $suffix = filter_input(INPUT_POST, 'suffix', FILTER_SANITIZE_NUMBER_INT);
                }

                if (empty($_POST["message"])) {
                    $messageErr = "message is required";
                } else {
                    $sent_message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
                }
            }

            // function call that calls all other functions.
            captcha_call();
      }
      
      
      
            /* 
             * function to insert the form info into the user_input table
             * $areaCode, the phone area code
             * $prefix, the prefix of phone number
             * $suffix, the suffix of phone number
             * $user_agent_info, the users os and browser info  
             * $ip_address, the ip address of the user
             * $sent_message, the message that the user wants to send
             */
            function insert_User_Input() {
                global $areaCode;
                global $prefix;
                global $suffix;
                global $user_agent_info;
                global $ip_address;
                global $sent_message;

                $dest_phone_num = $areaCode . $prefix . $suffix;
                $sent_message = filter_input(INPUT_POST, 'message');
                $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "freewtt");
                $sql = "insert into user_input ( ip_address, user_agent, dest_phone, message, date)
                   values ('$ip_address', '$user_agent_info', '$dest_phone_num', '$sent_message', CURDATE());";
                $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
            }

            /* 
             * function that uses the users ip address, os, and current date to 
             * return the number of sms messages the user has sent for that day
             * $user_agent_info, the users os and browser info  
             * $ip_address, the ip address of the user
             * $date_sent, the date the sms was sent
             * @return, the number of messages sent by a particular user in a day
             */
            function messages_Sent() {
                global $ip_address;
                global $user_agent_info;

                $date_sent = date('Y-m-d');
                $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "freewtt");
                $sql = "Select id from user_input where ip_address = '$ip_address' AND"
                        . " user_agent = '$user_agent_info' AND date = '$date_sent';";
                $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                if (mysqli_num_rows($result) > 0) {
                    return mysqli_num_rows($result);
                } else {
                    return 0;
                }
            }

            /* 
             * function that determines if a phone number is canadian and
             * creates an array of sms gateways for a specific telephone company
             * $province, province returned by fonefinder.net
             * $telco, telephone company returned by fonefinder.net
             * $sms_gateways, array of sms gateways for a specific tel company
             * @return boolean, returns true if phone number is canadian
             */
            function is_ca_wireless_telco() {
                global $province;
                global $telco;
                global $sms_gateways;

                $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "freewtt");
                $sql = "Select * from provinces where province = '" . $province . "';";
                $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                if (mysqli_num_rows($result) == 1) {
                    $sql = "Select * from canadian_sms_gateways where telco_name Like '%"
                            . $telco . "%';";
                    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                    while ($row = mysqli_fetch_assoc($result)) {
                        $sms_gateways[] = $row['gateway_address'];
                    }
                    if (mysqli_num_rows($result) >= 1) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
             
            /* 
             * function that builds the email string 'phone#@sms_gateway' and 
             * sends the message to the phone# specified. Calls other functions
             * to test if number is canadian, is wireless, if messages sent that
             * day do not exceed daily limit, and insert form data into database
             * $info_message, message that displays if message sent successful or error
             * @param $areaCode, the phone area code
             * @param $prefix, the prefix of phone number
             * @param $suffix, the suffix of phone number
             * $sms_gateways, array of sms gateways for a specific tel company
             * $sent_message, the message that the user wants to send
             * $name, the name of the user using the site
             * $max_num_sends, the constant specifying the daily limit of sms text sends
             * $client, the email string used in gmail function
             */
            function start() {
                global $info_message;
                global $areaCode;
                global $prefix;
                global $suffix;
                global $sms_gateways;
                global $sent_message;
                global $name;
                global $max_num_sends;

                scrape_fonefinder($areaCode, $prefix, $suffix);
                if (messages_Sent() < $max_num_sends) {
                    if (is_ca_wireless_telco() == true) {
                        $len = count($sms_gateways);
                        for ($i = 0; $i < $len; ++$i) {
                            $sub = "message from $name";
                            $client = $areaCode . $prefix . $suffix . "@" . $sms_gateways[$i];
                            gmail($client, $sub, $sent_message);
                            insert_User_Input();
                        }
                        $info_message = "<span class = \"success\">Your message has been sent.</span><br/><br/>";
                    } else {
                        $info_message = "<span class = \"err\">Please enter a Canadian Cellphone Number.</span><br/><br/>";
                    }
                } else {
                    $info_message = "<span class = \"err\">Daily limit of $max_num_sends text messages exceeded.</span><br/><br/>";
                }
            }

            /* 
             * function that checks if catcha string is correct and then invokes function start
             * $info_message, message that displays if message sent successful or error
             * $x, the captcha code
             * $y, the input string from user
             */
            function captcha_call() {
                global $info_message;
               // $x = $_SESSION['captcha']['code'];
                //$y = $_POST['capcha_input'];
                if ($_SESSION['captcha']['code'] == $_POST['capcha_input']) {
                    start();
                } 
                else {
                   $info_message = "<span class = \"err\">Please enter the Captcha Code.</span><br/><br/>";
                   header("freewtt_form.php");
                }
            }
      
      
      ?>



   <body>
        <link href="freewtt.css" rel="stylesheet" type="text/css" />
        <h1>Welcome to FreeWebToText.com</h1>
        <h2>Send SMS to any phone number easily with our web form.</h2>
        <?php echo $info_message; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label>Sender Name:</label> <input name="name" type="text" name="sender_name" required>*<br>
            <span class="error"> <?php echo $nameErr; ?></span><br>
            <label>destination phone number:</label> (<input value="" min='100' max="999" type= number name="areaCode" placeholder="###" required>)</label> 
            <span class="error"> <?php echo $areaCodeErr; ?></span>
            <input value="" min='100' max="999" type= number name="prefix" placeholder="###" required>-
            <span class="error"> <?php echo $prefixErr; ?></span>
        </label> <input value="" min='1000' max="9999" type= number name="suffix" placeholder="####" required>*
        <span class="error"> <?php echo $suffixErr; ?></span><br>
        <p>Message to send:</p><textarea cols="50" rows="5" maxlength="161" name="message" placeholder="Enter your message here..." required></textarea>
        <br>
<?php $_SESSION['captcha'] = simple_php_captcha();
echo "<br /><br /><img src = \"" . $_SESSION['captcha']['image_src'] . "\" >
            <br /><br />enter the capcha as you see it:<br /><br />
            <input type=\"text\" name=\"capcha_input\"><br /><br />";
?>
        <input type="submit">    
    </form>
</body>
</html>
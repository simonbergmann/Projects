<?php
//if ($_COOKIE["auth"] == "1") {
if (filter_input(INPUT_COOKIE, 'auth') == "1") {
    $display_block = "<h3>Good Luck on your play!</h3>"
            . "<p>Here are your Ticket Numbers to choose from.</p>";
} else {
    //redirect back to login form if not authorized
    header("Location: userlogin.html");
    exit;
}
?>

<html>
    <head>
        <link rel ="stylesheet" type ="text/css" href="styles.css">
        <title>Lotto Numbers</title>
    </head>
    <body>
        <?php
        echo "$display_block";

        function lotto649() {
            $arr649 = array();
            for ($i = 0; $i < 6; $i++) {
                $n = 0;
                $tmparr = array();
                while ($n < 6) {
                    $a = mt_rand(1, 49);
                    if (!(in_array($a, $tmparr))) {
                        $tmparr[] = $a;
                        $n++;
                    }
                }
                sort($tmparr);
                $arr649[] = $tmparr;
            }
            $str = "<table border = 1>";
            $str .= "<caption><img src = \"lotto6_49.png\" width = \"180 \" length = \"180 \"></caption>";
            for ($row = 0; $row < 6; $row++) {
                $str .= "<tr><th>Ticket " . ($row + 1) . ":</th>";
                for ($col = 0; $col < 6; $col++) {
                    $str .= "<th>" . $arr649[$row][$col] . "</th>";
                }
                $str .= "</tr>";
            }
            $str .= "</table><br/><br/>";
            echo $str;
        }

        function lottoMAX() {
            $arrMAX = array();
            for ($i = 0; $i < 6; $i++) {
                $n = 0;
                $tmparr = array();
                while ($n < 7) {
                    $a = mt_rand(1, 49);
                    if (!(in_array($a, $tmparr))) {
                        $tmparr[] = $a;
                        $n++;
                    }
                }
                sort($tmparr);
                $arrMAX[] = $tmparr;
            }
            global $str2;
            $str2 = "<table border = 1>";
            $str2 .= "<caption><img src = \"lottoMAX.png\" width = \"180 \" length = \"180 \"></caption>";
            for ($row = 0; $row < 6; $row++) {
                $str2 .= "<tr><th>Ticket " . ($row + 1) . ":</th>";
                for ($col = 0; $col < 7; $col++) {
                    $str2 .= "<th>" . $arrMAX[$row][$col] . "</th>";
                }
                $str2 .= "</tr>";
            }
            $str2 .= "</table>";
            echo $str2;
        }

        function disp649() {
            global $str;
            $str = "<table border = 1>";

            for ($row = 0; $row < 6; $row++) {
                $str .= "<tr>";
                for ($col = 0; $col < 6; $col++) {
                    $str .= "<td>" . $arr649[$row][$col] . "</td>";
                }
                $str .= "</tr>";
            }
            $str .= "</table><br/><br/>";
            echo $str;
        }

        function dispMAX() {
            global $str2;
            $str2 = "<table border = 1>";

            for ($row = 0; $row < 6; $row++) {
                $str2 .= "<tr>";
                for ($col = 0; $col < 7; $col++) {
                    $str2 .= "<td>" . $arrMAX[$row][$col] . "</td>";
                }
                $str2 .= "</tr>";
            }
            $str2 .= "</table>";
            echo $str2;
        }

        function IsChecked($chkname, $value) {
            if (!empty($_POST[$chkname])) {
                foreach ($_POST[$chkname] as $chkval) {
                    if ($chkval == $value) {
                        return true;
                    }
                }
            }
            return false;
        }

        if ((filter_input(INPUT_POST, 'LotMAX') == "LottoMAX") && (filter_input(INPUT_POST, 'Lotto_Num') == "Lotto649")) {
            lotto649();
            lottoMAX();
        } elseif (filter_input(INPUT_POST, 'LotMAX') == "LottoMAX") {
            lottoMAX();
        } elseif (filter_input(INPUT_POST, 'Lotto_Num') == "Lotto649") {
            lotto649();
        } else {
            echo "You need to check at least one checkbox";
        }
        ?>
    </body>
</html> 

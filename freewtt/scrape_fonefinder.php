<?php


global $is_wireless;  // true or false, and null if not assigned (should not happen)  
global $telco;   // Will contain first lower-case word of the telco name.  
global $province;  // will have the lower-case name of province if Canadian, null otherwise.  
//global $areaCode;  // 3 digits   //parameter for scrape_fonefinder()
//global $prefix;  // 3 digits     //parameter for scrape_fonefinder()
//global $suffix;  // 4 digits     //parameter for scrape_fonefinder()
//global $provinces; // array used to check if canadian telco. 


function scrape_fonefinder($areaCode, $prefix, $suffix) {
    /*
     * @param $areaCode: the area code of the subject number
     * @param $prefix: the three digit prefix of the subject number
     * @param $suffix: the four-digit suffix of the subject number
     * function to populate $is_wireless, $telco, and $province from fonefinder.net
     */

//clear the variables 
    global $is_wireless;
    global $telco;
    global $province;
    global $scrape_error;
    $is_wireless = $telco = $province = null;
    $scrape_error = "";
    //$result;
//create the url...
    $ff_string = "http://www.fonefinder.net/findome.php?npa=" . $areaCode . "&nxx=" . $prefix . "&thoublock=" . $suffix . "&usaquerytype=Search+by+Number&cityname=";

//capture the page as a long string
    $result .= file_get_contents($ff_string);

    //narrow the string///
    $marker = 'Detail';  // the marker from where to start. 
    $pos = strpos($result, $marker);  //the position of the marker in the big string.  

    $result = strip_tags(substr($result, $pos, 500), "<td>"); // narrow the result to 500 minus all the tags (except for <td>) characters starting from the marker.  
//// replace all the td tags with double underscores "__".  
    $result = str_replace("<TD>", "__", $result);

//stick the result into an array delimited by "__"
    global $resultArr;
    $resultArr = explode("__", $result);

    //get the telco
    $telco = $resultArr[5];

    $space_telco = explode(" ", $telco);

    $telco = $space_telco[0];

    $hyphen_telco = explode("-", $telco);

    $telco = strtolower($hyphen_telco[0]);

    $province = strtolower($resultArr[4]);


//determin if the phone number provided is wireless (the term "pcs" and "cap" also refer to wireless networks.)
    if ((strtolower(substr($resultArr[6], 0, 8)) == 'wireless') || (strtolower($resultArr[6]) == 'pcs') || (strtolower($resultArr[6]) == 'cap')) {
        
        global $is_wireless;
        $is_wireless = true;
        
    } elseif ($telco == 'fido') {

        $is_wireless = true;
    } else {

        $is_wireless = false;
    }
}


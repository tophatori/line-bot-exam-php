<?php

session_start();
include_once "../connectdb.php";
$a_user_id = mysqli_real_escape_string($conndb, $_REQUEST['uid']);
$strSQL = "SELECT *,CONCAT(address, ' ', road,' ต.' ,district, ' อ.' , city, ' จ.' ,province) as AddressCus  FROM tb_ext  where passport = '" . $a_user_id . "' or phonenumber='" . $a_user_id . "'";
$result = mysqli_query($conndb, $strSQL);
$rs = mysqli_fetch_array($result);
if (!$rs) {
    echo "this user id doesn't exist!";
} else {
    echo $rs["passport"];
    echo "$" . $rs["name"];
    echo "$" . $rs["nationality"];
    echo "$" . $rs["sex"];
    echo "$" . $rs["birthday"];
    echo "$" . $rs["passport"];
    echo "$" . $rs["entrance"];
    echo "$" . $rs["visaext"];
    echo "$" . $rs["phonenumber"];
    echo "$" . $rs["AddressCus"];
    echo "$" . $rs["sended_sms"];
}
mysqli_close($conndb);
?>



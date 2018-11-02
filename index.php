<?php

$strAccessToken = "gKSMPtAKmrHv274eUgKUNh22PXLCM69B209fRk76ZeSfaVJO0oRXoh6VHorIS0zk3cQbaOd7c/Vjhs7U7u2WlLHJnLMyp3IW5572bgyfpRVM8UV6WXiZeHKzYGWkdNP4kAK6nbg0sP4c4i1MnwgcCQdB04t89/1O/w1cDnyilFU=";

$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);

$strUrl = "https://api.line.me/v2/bot/message/reply";

$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";

$show = substr($arrJson['events'][0]['message']['text'], 0, 1);
$idcard = substr($arrJson['events'][0]['message']['text'], 1);
if ($show == "$") {
    if ($idcard != "") {
        $urlWithoutProtocol = "http://vpn.idms.pw/auth/selectgps.php?uid=" . $idcard;
        $isRequestHeader = FALSE;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlWithoutProtocol);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $productivity = curl_exec($ch);
        curl_close($ch);
        //$json_a = json_decode($productivity, true);
        $arrbn_id = explode("$", $productivity);
        //print_r($arrbn_id);
//        if (is_numeric(substr($arrbn_id[0], 0, 1))) {
//        echo $objResult["customer_name"];
//        echo "#" . $objResult["Latitude"];
//        echo "#" . $objResult["Longitude"];
//        echo "#" . $objResult["province"];
//        echo "#" . $objResult["contact_tel"];



        $customer_name = $arrbn_id[0];
        $Latitude = $arrbn_id[1];
        $Longitude = $arrbn_id[2];
        $province = $arrbn_id[3];
        $contact_tel = $arrbn_id[4];

        $urlWithoutProtocol = "http://vpn.idms.pw/auth/auth.php?pid=" . $arrbn_id[0] . "&cid=" . $arrbn_id[1];
        $isRequestHeader = FALSE;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlWithoutProtocol);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $productivity = curl_exec($ch);
        curl_close($ch);
        $arrPostData = array();
        $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
        $arrPostData['messages'][0]['type'] = "text";
        $arrPostData['messages'][0]['text'] = "ชื่อ : " 
                . $customer_name . "\r\n"
                . "เบอร์โทรศํพท์ : ". $contact_tel . "\r\n"
                . "สถานที่ : " . $province . "\r\n"
                . "พิกัด : https://www.google.co.th/maps/place/" . $Latitude . "," . $Longitude;
        //print_r($productivity);
//        }
        //$json_a = json_decode($productivity, true);
        //echo $productivity ;
    }
} else {

    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = "ข้อความไม่ถูกต้อง กรุณากรอกเป็นแบบนี้ (ตัวอย่าง  $BT00009 (รหัสตู้บุญเติม))";
}


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $strUrl);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close($ch);
?>




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
if ($show == "#") {
    if ($idcard != "") {
        $urlWithoutProtocol = "http://vpn.idms.pw/auth/selectgps.php?uid=" . $idcard;
        $isRequestHeader = FALSE;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlWithoutProtocol);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $productivity = curl_exec($ch);
        curl_close($ch);
        //$json_a = json_decode($productivity, true);
        $arrbn_id = explode("#", $productivity);
        //print_r($arrbn_id);
//        echo $objResult["customer_name"];
//        echo "#" . $objResult["contact_tel"];
//        echo "#" . $objResult["Latitude"];
//        echo "#" . $objResult["Longitude"];
//        echo "#" . $objResult["address"];
//        echo "#" . $objResult["street"];
//        echo "#" . $objResult["district"];
//        echo "#" . $objResult["city"];
//        echo "#" . $objResult["province"];


        $customer_name = $arrbn_id[0];  //ชื่อ
        $contact_tel = $arrbn_id[1]; //เบอร์โทรศัพท์
        $Latitude = $arrbn_id[2]; // ละติดจูด
        $Longitude = $arrbn_id[3]; // ลองติจูด
        $address = $arrbn_id[4]; // address
        $street = $arrbn_id[5]; // street
        $district = $arrbn_id[6]; // district
        $city = $arrbn_id[7]; // city
        $province= $arrbn_id[8]; // province
        $arrPostData = array();
        $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
        $arrPostData['messages'][0]['type'] = "text";
        $arrPostData['messages'][0]['text'] = "ชื่อ : ". $customer_name . "\r\n"
                . "โทรศํพท์ : " . $contact_tel . "\r\n"
                . "สถานที่ : " . $address." ".$street." ".$district." ".$city." ".$province . "\r\n"
                . "พิกัด : https://www.google.co.th/maps/place/" . $Latitude . "," . $Longitude;
        //print_r($productivity);
//        }
        //$json_a = json_decode($productivity, true);
        //echo $productivity ;
    }
} else {

//    $arrPostData = array();
//    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
//    $arrPostData['messages'][0]['type'] = "text";
//    $arrPostData['messages'][0]['text'] = "ข้อความไม่ถูกต้อง กรุณากรอกเป็นแบบนี้ (ตัวอย่าง  '#BT00009' (รหัสตู้บุญเติม))";
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




<?php

$strAccessToken = "k6DsHWvM/uu/nwteixMyTAPWLGFlUuaOLxwMgh1MdSBjbN8eIkn1bqsqnFOe9WIC/JXvaBHNSHkmw5pF/uuDhlVjI3+m8+FU/oVzspagAFJrAj/tAtGJOKWp/ohH3HFdVpmJ5f9GtTcsZs+E0ezfJQdB04t89/1O/w1cDnyilFU=";

$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);

$strUrl = "https://api.line.me/v2/bot/message/reply";

$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";

//รับข้อความจากผู้ใช้
$show = substr($arrJson['events'][0]['message']['text'], 0, 1);
$passport = substr($arrJson['events'][0]['message']['text'], 1);
if ($show == "#") {
    if ($passport != "") {
        $urlWithoutProtocol = "http://immpataya.donot.pw/imm/Line/overcheck.php?uid=" . $passport;
        $isRequestHeader = FALSE;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlWithoutProtocol);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $productivity = curl_exec($ch);
        curl_close($ch);
     
//        $json_a = json_decode($productivity, true);
        $arrbn_id = explode("$", $productivity);
        $id_passport = $arrbn_id[0];  //No. Passport
        $name = $arrbn_id[1];  //ชื่อ
        $nationality = $arrbn_id[2]; //สัญชาติ
        $sex = $arrbn_id[3]; // เพศ
        $birthday = $arrbn_id[4]; // วันเกิด
        $passport = $arrbn_id[5]; // เลขที่ passport
        $entrance = $arrbn_id[6]; // วันที่เข้า
        $visaext = $arrbn_id[7]; // วันครบกำหนด
        $phonenumber = $arrbn_id[8]; // เบอร์โทรศํพท์
        $AddressCus = $arrbn_id[9]; // ที่อยู่
        $sended_sms = $arrbn_id[10]; // ที่อยู่


        $arrPostData = array();
//        $arrPostData['to'] = $id;
        $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
        $arrPostData['messages'][0]['type'] = "text";
        $arrPostData['messages'][0]['text'] = ""
                . "ชื่อ-สกุล : " . $name . "\r\n"
                . "สัญชาติ : " . $nationality . "\r\n"
                . "เบอร์โทรศัพท์ : " . $phonenumber . "\r\n"
                . "ที่อยู่ : " . $AddressCus . "\r\n"
                . "วันที่ครบกำหนด : " . $visaext . "\r\n";
    }
} else {

    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = "ข้อความไม่ถูกต้อง กรุณากรอกเป็นแบบนี้ (ตัวอย่าง  '#BT00009' (รหัสตู้บุญเติม))";
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




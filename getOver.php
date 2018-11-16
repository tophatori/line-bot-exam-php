<?php

$strAccessToken = "fIiQxJSukgOlddmOkK3l5Dr4tXpRYJyw1UPiwpG2SHUeyGm71lqYIoLKZ939hnKU/JXvaBHNSHkmw5pF/uuDhlVjI3+m8+FU/oVzspagAFL7MPCIF6wKcJbgEffGtmDS7JyMMLBkPvqmVJor3Unr9wdB04t89/1O/w1cDnyilFU=";


$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);
$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";



function pushMsg($arrayHeader, $arrayPostData) {
    $strUrl = "https://api.line.me/v2/bot/message/push";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $strUrl);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayPostData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
}



//รับข้อความจากผู้ใช้
$show = substr($arrJson['events'][0]['message']['text'], 0, 1);
$passport = substr($arrJson['events'][0]['message']['text'], 1);

if (isset($arrayJson['events'][0]['source']['userId'])) {
    $id = $arrayJson['events'][0]['source']['userId'];
} else if (isset($arrayJson['events'][0]['source']['groupId'])) {
    $id = $arrayJson['events'][0]['source']['groupId'];
} else if (isset($arrayJson['events'][0]['source']['room'])) {
    $id = $arrayJson['events'][0]['source']['room'];
}
if ($show == "#") {
    if ($passport != "") {
        $urlWithoutProtocol = "http://immpataya.donot.pw/imm/Line/overcheck.php?uid=" . $passport;
        $isRequestHeader = FALSE;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlWithoutProtocol);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $productivity = curl_exec($ch);
        curl_close($ch);
        //$json_a = json_decode($productivity, true);
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
         $arrPostData['to'] = $id;
        //$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
        $arrPostData['messages'][0]['type'] = "text";
        $arrPostData['messages'][0]['text'] = ""
                . "ชื่อ-สกุล : " . $name . "\r\n"
                . "สัญชาติ : " . $nationality . "\r\n"
                . "เบอร์โทรศัพท์ : " . $phonenumber . "\r\n"
                . "ที่อยู่ : " . $AddressCus . "\r\n"
                . "วันที่ครบกำหนด : " . $visaext . "\r\n";
         pushMsg($arrayHeader, $arrPostData);
    }
} else {

    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = "ข้อความไม่ถูกต้อง กรุณากรอกเป็นแบบนี้ (ตัวอย่าง  '%เลขที่พาสปอร์ต' )";
}

?>


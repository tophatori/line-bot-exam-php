<?php

$strAccessToken = "roMPb5NmOWgQ8S0j/Ozs9/L/Gr8fiyNYDz9hJEGZJhQub76bsCgrq5k5ESsvltErm0IwK3HJZ/7Uh/YDH3DFU/gaBek2kySbx/w7/+F8w+FqMdL3EQ0jZdvwXCoNQCWZFeq2pOVyLtlLG7CB3v2EcwdB04t89/1O/w1cDnyilFU=";

$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);

$strUrl = "https://api.line.me/v2/bot/message/reply";

$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";

$show = substr($arrJson['events'][0]['message']['text'], 0, 1);
$tel = substr($arrJson['events'][0]['message']['text'], 1);
if ($show == "#") {
    if ($tel != "") {
        $urlWithoutProtocol = "http://vpn.idms.pw/auth/search_tel.php?tel=" . $tel;
        $isRequestHeader = FALSE;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlWithoutProtocol);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $productivity = curl_exec($ch);
        curl_close($ch);
        //$json_a = json_decode($productivity, true);
        $arrbn_id = explode("#", $productivity);

        
        $Topup_name = $arrbn_id[1];  //รหัสตู้
        $Mobile_Number = $arrbn_id[2]; //เบอร์โทรศัพท์
        $Real_Service_Amount = $arrbn_id[3]; // //จำนวนที่เติม
        $Service_Type = $arrbn_id[4]; // เครือข่าย
        $Start_Date = $arrbn_id[5]; // วันที่เติม
       
        $customer_name = $arrbn_id[6]; // //ชื่อตู้
        $addrss = $arrbn_id[7]; // ที่อยู่
        $Latitude = $arrbn_id[8]; // ละติจูด
        $Longitude = $arrbn_id[9]; // ลอง
        $arrPostData = array();
        $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
        $arrPostData['messages'][0]['type'] = "text";
        $arrPostData['messages'][0]['text'] = ""
                . "เบอร์โทร : " . $Mobile_Number . "\r\n"
                . "จำนวนที่เติม : " . $Real_Service_Amount . "\r\n"
                . "เครือข่าย : " . $Service_Type . "\r\n"
                 ."วันที่เติม : " . $Start_Date . "\r\n"
                . "รหัสตู้ : " . $Topup_name . "\r\n"
                . "ชื่อตู้ : " . $customer_name . "\r\n"
                . "ที่อยู่ : " . $addrss . "\r\n"
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




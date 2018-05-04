<?php

require "vendor/autoload.php";
$access_token = 'roMPb5NmOWgQ8S0j/Ozs9/L/Gr8fiyNYDz9hJEGZJhQub76bsCgrq5k5ESsvltErm0IwK3HJZ/7Uh/YDH3DFU/gaBek2kySbx/w7/+F8w+FqMdL3EQ0jZdvwXCoNQCWZFeq2pOVyLtlLG7CB3v2EcwdB04t89/1O/w1cDnyilFU=';

$channelSecret = '75c03f392f6e53d662d6f5a8db9e421f';

$pushID = 'U7ef7a449f2a5c2057eacfc02ba2eb286';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);


$strAccessToken = "roMPb5NmOWgQ8S0j/Ozs9/L/Gr8fiyNYDz9hJEGZJhQub76bsCgrq5k5ESsvltErm0IwK3HJZ/7Uh/YDH3DFU/gaBek2kySbx/w7/+F8w+FqMdL3EQ0jZdvwXCoNQCWZFeq2pOVyLtlLG7CB3v2EcwdB04t89/1O/w1cDnyilFU=";

$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);
$strUrl = "https://api.line.me/v2/bot/message/reply";

$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";
//$strexp = isset($_REQUEST['strexp']) ? $_REQUEST['strexp'] : '';
$strexp = $arrJson['events'][0]['message']['text'];
$strchk = str_split($strexp);

//echo $strchk[0];
$arrayloop = array();
if ($strchk[0] == "#") {
    $arrstr = explode("#", $strexp);
    for ($k = 1; $k < count($arrstr); $k++) {

        $strchk = "#" . $arrstr[$k];
        $show = substr($strchk, 0, 1);
        $space = iconv("tis-620", "utf-8", substr($strchk, 1, 1));
        $idcard = substr($strchk, 1);
        if ($space != "") {
            if ($idcard != "") {
                $countid = strlen($idcard);
                $chkid = substr($idcard, 0, 13);
                if (is_numeric($chkid)) {
                    $countid = strlen($chkid);
                    if ($countid == "13") {
                        $idcard = $chkid;
                    }
                }
                if (is_numeric($idcard)) {
                    $countid = strlen($idcard);
                    if ($countid == "13") {
                        $request = "operation=Add&a_cardid=" . $idcard;

                        $urlWithoutProtocol = "pdc.police.go.th/arrest/check_arrest.php?" . $request;
                        $isRequestHeader = FALSE;
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $urlWithoutProtocol);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $productivity = curl_exec($ch);
                        curl_close($ch);
                        $json_a = json_decode($productivity, true);
                        $a_cardid = "";
                        $a_fullname = "";
                        $a_charged = "";
                        $a_link = "";
                        $police = "";
                        $phone = "";
                        $status = "";
                        $output = array();
                        $count_a = 0;
                        foreach ($json_a as $key) {
                            foreach ($key as $key_b => $value) {
                                if ($key_b == "name") {
                                    $a_fullname = $value;
                                    $count_a++;
                                }
                                if ($key_b == "idcard") {
                                    $a_cardid = $value;
                                }
                                if ($key_b == "offence") {
                                    if ($value != "") {
                                        $a_charged = "ข้อหา : " . $value;
                                    } else {
                                        $a_charged = "";
                                    }
                                }

                                if ($key_b == "file") {
                                    if ($value != "") {
                                        $a_link = "http://pdc.police.go.th/arrest/data/pdf/" . $value;
                                    } else {
                                        $a_link = "";
                                    }
                                }

                                if ($key_b == "policestation") {
                                    if ($value != "") {
                                        $police = "หน่วย : " . $value;
                                    } else {
                                        $police = "";
                                    }
                                }

                                if ($key_b == "phone") {
                                    if ($value != "") {
                                        $phone = "เบอร์ : " . "0" . $value;
                                    } else {
                                        $phone = "";
                                    }
                                }
                                if ($key_b == "status") {
                                    if ($value != "") {
                                        if ($value == "0") {
                                            $status = "ยังไม่ถูกจับกุม";
                                        } else {
                                            $status = "จับกุมแล้ว";
                                        }
                                    } else {
                                        $status = "";
                                    }
                                }
                                array_push($output, $a_charged . chr(10) . $a_link . chr(10) . $police . chr(10) . $phone . chr(10) . $status);
                            }
                        }
                        $txt = "บุคคลดังกล่าวมีหมายจับ" . chr(10) . "เลขบัตร  : " . $a_cardid . chr(10) . "ชื่อ-นาสกุล : " . $a_fullname;

                        if ($a_fullname != "") {
                            $msg = "";
                            $cardid = "";
                            $name = "";
                            $tb_status = "";

                            $input = 'http://vpn.idms.pw:9898/polis/imagebyte?id=' . $a_cardid;
                            $dirimg = 'pic/';            // directory in which the image will be saved
                            $localfile = $dirimg . $a_cardid . '.jpg';         // set image name the same as the file name of the source
                            // create the file with the image on the server

                            $r = file_put_contents($localfile, getContentUrl($input));
                            //"https://www.detectivepolice1.com/pic/".$a_cardid.".jpg";
                            $status = "";

                            $num = 2;
                            $coutarr = count($output);
                            $txt .= chr(10) . $output[$coutarr - 1];

                            $count = $count_a - 1;
                            if ($r) {
                                $status = "1";
                            } else {
                                $status = "2";
                            }

                            $arrPostData = array();
                            $arrPostData["idcard"] = $a_cardid;
                            $arrPostData["detail"] = $txt;
                            $arrPostData["status"] = $status;
                            //print_r($arrPostData);
                            array_push($arrayloop, $arrPostData);
                        } else {
                            $txt = "ไม่พบหมายจับของบุคคลดังกล่าว " . $idcard;

                            $arrPostData = array();
                            $arrPostData["idcard"] = $a_cardid;
                            $arrPostData["detail"] = $txt;
                            $arrPostData["status"] = "0";
                            array_push($arrayloop, $arrPostData);
                        }
                    } else {
                        $arrPostData = array();
                        $arrPostData["idcard"] = $idcard;
                        $arrPostData["detail"] = "เลขบัตรประชาชนไม่ถูกต้อง : " . $idcard;
                        $arrPostData["status"] = "0";
                        array_push($arrayloop, $arrPostData);
                    }
                } else {
                    $idcard = str_replace(' ', '_', $idcard);
                    $idcard = str_replace(' ', '', $idcard);
                    $request = "operation=Add&a_cardid=" . $idcard;
                    $urlWithoutProtocol = "pdc.police.go.th/arrest/check_arrest.php?" . $request;
                    $isRequestHeader = FALSE;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $urlWithoutProtocol);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $productivity = curl_exec($ch);
                    curl_close($ch);
                    $json_b = json_decode($productivity, true);

                    $a_cardid = "";
                    $a_fullname = "";
                    $a_charged = "";
                    $a_link = "";
                    $police = "";
                    $phone = "";
                    $status = "";

                    $output = array();
                    $count_a = 0;
                    foreach ($json_b as $key) {
                        foreach ($key as $key_b => $value) {
                            if ($key_b == "name") {
                                $a_fullname = $value;
                                $count_a++;
                            }
                            if ($key_b == "idcard") {
                                $a_cardid = $value;
                            }
                            if ($key_b == "offence") {
                                if ($value != "") {
                                    $a_charged = "ข้อหา : " . $value;
                                } else {
                                    $a_charged = "";
                                }
                            }
                            if ($key_b == "file") {
                                if ($value != "") {
                                    $a_link = "http://pdc.police.go.th/arrest/data/pdf/" . $value;
                                } else {
                                    $a_link = "";
                                }
                            }
                            if ($key_b == "policestation") {
                                if ($value != "") {
                                    $police = "หน่วย : " . $value;
                                } else {
                                    $police = "";
                                }
                            }
                            if ($key_b == "phone") {
                                if ($value != "") {
                                    $phone = "เบอร์ : " . "0" . $value;
                                } else {
                                    $phone = "";
                                }
                            }
                            if ($key_b == "status") {
                                if ($value != "") {
                                    if ($value == "0") {
                                        $status = "ยังไม่ถูกจับกุม";
                                    } else {
                                        $status = "จับกุมแล้ว";
                                    }
                                } else {
                                    $status = "";
                                }
                            }
                            array_push($output, $a_charged . chr(10) . $a_link . chr(10) . $police . chr(10) . $phone . chr(10) . $status);
                        }
                    }
                    $txt = "บุคคลดังกล่าวมีหมายจับ" . chr(10) . "เลขบัตร  : " . $a_cardid . chr(10) . "ชื่อ-นาสกุล : " . $a_fullname;

                    if ($a_fullname != "") {
                        $msg = "";
                        $cardid = "";
                        $name = "";
                        $tb_status = "";

                        $input = 'http://vpn.idms.pw:9898/polis/imagebyte?id=' . $a_cardid;
                        $dirimg = 'pic/';            // directory in which the image will be saved
                        $localfile = $dirimg . $a_cardid . '.jpg';         // set image name the same as the file name of the source
                        // create the file with the image on the server

                        $r = file_put_contents($localfile, getContentUrl($input));
                        //"https://www.detectivepolice1.com/pic/".$a_cardid.".jpg";
                        $status = "";

                        $num = 2;
                        $coutarr = count($output);
                        $txt .= chr(10) . $output[$coutarr - 1];

                        $count = $count_a - 1;
                        if ($count != 0) {
                            $txt .= chr(10) . "หมายจับอีก " . $count;
                        }
                        if ($r) {
                            $status = "1";
                        } else {
                            $status = "2";
                        }


                        $arrPostData = array();
                        $arrPostData["idcard"] = $a_cardid;
                        $arrPostData["detail"] = $txt;
                        $arrPostData["status"] = $status;
                        array_push($arrayloop, $arrPostData);
                    } else {
                        $txt = "ไม่พบหมายจับของบุคคลดังกล่าว " . $idcard;

                        $arrPostData = array();
                        $arrPostData["idcard"] = $idcard;
                        $arrPostData["detail"] = "ไม่พบหมายจับของบุคคลดังกล่าว " . $idcard;
                        $arrPostData["status"] = "0";
                        array_push($arrayloop, $arrPostData);
                    }
                }
            }
        }
    }
} else if ($strchk[0] == "*") {
    $arrstr = explode("*", $strexp);
    for ($k = 1; $k < count($arrstr); $k++) {
        $strchk = "*" . $arrstr[$k];
        $idcard = substr($strchk, 1);
        $chkid = substr($idcard, 0, 13);
        if (is_numeric($chkid)) {
            $countid = strlen($chkid);
            if ($countid == "13") {
                $idcard = $chkid;
            }
        }
        if (is_numeric($idcard)) {
            $countid = strlen($idcard);
            if ($countid == "13") {

                $input = 'http://vpn.idms.pw:9898/polis/imagebyte?id=' . $idcard;
                $dirimg = 'pic/';            // directory in which the image will be saved
                $localfile = $dirimg . $idcard . '.jpg';         // set image name the same as the file name of the source
                //echo $localfile;
                // create the file with the image on the server

                $r = file_put_contents($localfile, getContentUrl($input));

                $status = "";
                $txt = "";
                if ($r) {
                    $status = "3";
                } else {
                    $status = "2";
                }
                $arrPostData = array();
                $arrPostData["idcard"] = $idcard;
                $arrPostData["detail"] = $txt;
                $arrPostData["status"] = $status;
                //print_r($arrPostData);
                array_push($arrayloop, $arrPostData);
            }
        }
    }
} else if ($strchk[0] == "@") {
    $arrstr = explode("@", $strexp);
    for ($k = 1; $k < count($arrstr); $k++) {
        $strchk = "@" . $arrstr[$k];
        $idcard = substr($strchk, 1);
        $chkid = substr($idcard, 0, 13);
        if (is_numeric($chkid)) {
            $countid = strlen($chkid);
            if ($countid == "13") {
                $idcard = $chkid;
            }
        }
        if (is_numeric($idcard)) {
            $countid = strlen($idcard);
            if ($countid == "13") {


                $request = "a_cardid=" . $idcard;

                $urlWithoutProtocol = "http://vpn.idms.pw:81/searchphone/api_chkphonenum.php?" . $request;
                $isRequestHeader = FALSE;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $urlWithoutProtocol);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $productivity = curl_exec($ch);
                curl_close($ch);
                $json_b = json_decode($productivity, true);
                print_r($productivity);
                $dpn_phone = "";
                $dpn_idcard = "";
                foreach ($json_b as $key) {
                    foreach ($key as $key_b => $value) {
                        if ($key_b == "dpn_phone") {
                            $dpn_phone .= $value . " , ";
                            $count_a++;
                        }
                        if ($key_b == "dpn_idcard") {
                            $dpn_idcard = $value;
                        }
                    }
                }
                $status = "2";
                $arrPostData = array();
                $arrPostData["idcard"] = $idcard;
                $arrPostData["detail"] = $dpn_phone;
                $arrPostData["status"] = $status;
                //print_r($arrPostData);
                array_push($arrayloop, $arrPostData);
            }
        }
    }
}


//เก็บค่าถ้าต้องการขอข้อมูลททั้งหมด  
// เลขบัตร แสดงภาพใบหน้า + หมายจับ
// ค้นเลขบัตร แสดงหมายจับ + ภาพใบหน้า
//  + เบอร์โทร


$arrPostData = array();
$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
$num = 0;
foreach ($arrayloop as $loop) {
    $idcard = "";
    $status = "";
    $detail = "";
    foreach ($loop as $key => $value) {
        if ($key == "idcard") {
            $idcard = $value;
        }
        if ($key == "status") {
            $status = $value;
        }
        if ($key == "detail") {
            $detail = $value;
        }
    }
    if ($status == "1") {
        $arrPostData['messages'][$num]['type'] = "image";
        $arrPostData['messages'][$num]['originalContentUrl'] = "https://www.detectivepolice1.com/pic/" . $idcard . ".jpg";
        $arrPostData['messages'][$num]['previewImageUrl'] = "https://www.detectivepolice1.com/pic/" . $idcard . ".jpg";
        $num++;
    }
    if ($status == "3") {
        $arrPostData['messages'][$num]['type'] = "image";
        $arrPostData['messages'][$num]['originalContentUrl'] = "https://www.kitsada.com/pic/" . $idcard . ".jpg";
        $arrPostData['messages'][$num]['previewImageUrl'] = "https://www.kitsada.com/pic/" . $idcard . ".jpg";
        $num++;
    }
    if ($detail != "") {

        $arrPostData['messages'][$num]['type'] = "text";
        $arrPostData['messages'][$num]['text'] = $detail;
        $num++;
    }
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

function getContentUrl($url) {
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/21.0 (compatible; MSIE 8.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_TIMEOUT, 200);
    curl_setopt($ch, CURLOPT_AUTOREFERER, false);
    curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);    // Follows redirect responses
    // gets the file content, trigger error if false

    $file = curl_exec($ch);

    if ($file === false)
        trigger_error(curl_error($ch));
    curl_close($ch);
    return $file;
}



$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($result);
$response = $bot->pushMessage($pushID, $textMessageBuilder);

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

?>



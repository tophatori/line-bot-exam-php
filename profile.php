<?php


$access_token = 'roMPb5NmOWgQ8S0j/Ozs9/L/Gr8fiyNYDz9hJEGZJhQub76bsCgrq5k5ESsvltErm0IwK3HJZ/7Uh/YDH3DFU/gaBek2kySbx/w7/+F8w+FqMdL3EQ0jZdvwXCoNQCWZFeq2pOVyLtlLG7CB3v2EcwdB04t89/1O/w1cDnyilFU=
';

$userId = 'Uffa138efe037e6e889d0b0f4a871c005';

$url = 'https://api.line.me/v2/bot/profile/'.$userId;

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;


<?php



require "vendor/autoload.php";

$access_token = 'roMPb5NmOWgQ8S0j/Ozs9/L/Gr8fiyNYDz9hJEGZJhQub76bsCgrq5k5ESsvltErm0IwK3HJZ/7Uh/YDH3DFU/gaBek2kySbx/w7/+F8w+FqMdL3EQ0jZdvwXCoNQCWZFeq2pOVyLtlLG7CB3v2EcwdB04t89/1O/w1cDnyilFU=';

$channelSecret = '0b9d12d8440f8e72ace6f8bb98139090';

$pushID = 'U866aacc04480721fce435195aa42a9c6';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello world');
$response = $bot->pushMessage($pushID, $textMessageBuilder);

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();








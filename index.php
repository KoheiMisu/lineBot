<?php

$line = [
    'channelId' => '1461774589',
    'channelSecret' => '81f0768fd4100d25d1ca39e8eb6fa834',
    'channelMid' => 'u71fa2f99caf33552f6c33e09683f4049',
    'FixieUrl' => 'https://line-cooking-bot.herokuapp.com:443',
    'AppName' => 'cooking'
];


error_log("START: PHP");
$phpInput = json_decode(file_get_contents('php://input'));
$to = $phpInput->{"result"}[0]->{"content"}->{"from"};
$text = $phpInput->{"result"}[0]->{"content"}->{"text"};
$response_content = getResponseContent($text);
$post_data = ["to" => [$to], "toChannel" => "1383378250", "eventType" => "138311608800106203", "content" => $response_content];
$ch = curl_init("https://trialbot-api.line.me/v1/events");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, createHttpHeader());
curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
curl_setopt($ch, CURLOPT_PROXY, $line['FixieUrl']);
curl_setopt($ch, CURLOPT_PROXYPORT, 80);
$result = curl_exec($ch);
curl_close($ch);
error_log(json_encode($result));
error_log("END: PHP");


function createHttpHeader() {
    $header = array(
        'Content-Type: application/json; charset=UTF-8',
        'X-Line-ChannelID: ' . $line['channelId'],
        'X-Line-ChannelSecret: ' . $line['channelSecret'],
        'X-Line-Trusted-User-With-ACL: ' . $line['channelMid']
    );
    return $header;
}


function getResponseContent($text) {
    if ($text == "タッカラプトポッポルンガプピリットパロ") {
        return createTextResponse("ok!!!!!!!!!");
        // $imageUrl = "http://" . $line['AppName'] . ".herokuapp.com/image/polunga.png";
        // return createImageResponse($imageUrl, $imageUrl);
    } else {
        return createTextResponse("合言葉を言ってください");
    }
}


function createTextResponse($message) {
    return ['contentType' => 1, "toType" => 1, "text" => $message];
}


function createImageResponse($imageUrl, $thumbnailImageUrl) {
    return ['contentType' => 2, "toType" => 1, 'originalContentUrl' => $imageUrl, "previewImageUrl" => $thumbnailImageUrl];
}

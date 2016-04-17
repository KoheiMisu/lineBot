<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$line = [
    'channelId' => '1461774589',
    'channelSecret' => '81f0768fd4100d25d1ca39e8eb6fa834',
    'channelMid' => 'u71fa2f99caf33552f6c33e09683f4049',
    'FixieUrl' => 'http://fixie:V2608KBbfSyxAGC@velodrome.usefixie.com:80',
    'AppName' => 'cooking'
];


$app->post('/callback', function (Request $request) use ($app) {
    $client = new GuzzleHttp\Client();

    $body = json_decode($request->getContent(), true);
    foreach ($body['result'] as $msg) {
        if (!preg_match('/(ぬるぽ|ヌルポ|ﾇﾙﾎﾟ|nullpo)/i', $msg['content']['text'])) {
            continue;
        }

        $resContent = $msg['content'];
        $resContent['text'] = 'ｶﾞｯ';

        $requestOptions = [
            'body' => json_encode([
                'to' => [$msg['content']['from']],
                'toChannel' => 1383378250, # Fixed value
                'eventType' => '138311608800106203', # Fixed value
                'content' => $resContent,
            ]),
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
                'X-Line-ChannelID' => $line['channelId'],
                'X-Line-ChannelSecret' => $line['channelSecret'],
                'X-Line-Trusted-User-With-ACL' => $line['channelMid'],
            ],
            'proxy' => [
                'https' => $line['FixieUrl'],
            ],
        ];

        try {
            $client->request('post', 'https://trialbot-api.line.me/v1/events', $requestOptions);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    return 'OK';
});

$app->run();

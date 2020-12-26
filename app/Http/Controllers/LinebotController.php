<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder;

class LinebotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $client;
    private $bot;
    private $channel_access_token;
    private $channel_secret;
    public function __construct()
    {
        $this->channel_access_token = env('CHANNEL_ACCESS_TOKEN');
        $this->channel_secret = env('CHANNEL_SECRET');
        $httpClient = new CurlHTTPClient($this->channel_access_token);
        $this->bot = new LINEBot($httpClient, ['channelSecret' => $this->channel_secret]);
        $this->client = $httpClient;
    }
    public function index(Request $request)
    {
        $bot = $this->bot;
        $signature = $request->header(\LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE);
        $body = $request->getContent();
        try
        {
            $events = $bot->parseEventRequest($body, $signature);
        }
        catch(\Exception $e)
        {
            Log::error($e->getMessage());
        }
        foreach ($events as $event)
        {
            $replyToken = $event->getReplyToken();
            if ($event instanceof MessageEvent)
            {
                $message_type = $event->getMessageType();
                $text = $event->getText();
                switch ($message_type)
                {   
                    case 'text':
                    if(strpos($text,'#') !== false){
                        // $bot->replyText($replyToken, $text);
                        $message=  [{
                                    'type' => 'text',
                                    'text' => '您好，這是一個範例 Bot OuO
                                    範例程式開源至 GitHub (包含教學)：
                                    https://github.com/GoneTone/line-example-bot-php'
                                }];
                        $bot->replyMessage($replyToken,$message);
                        break;
                    }
                    $bot->replyText($replyToken, $tet);
                    break;
                }
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

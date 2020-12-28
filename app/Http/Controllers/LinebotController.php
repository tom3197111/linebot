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
            $messageBuilder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
            // file_put_contents( 'tmp/ECPay.txt', $events, FILE_APPEND );

        }
        catch(\Exception $e)
        {
            Log::error($e->getMessage());
        }
        foreach ($events as $event)
        {   

            $replyToken = $event->getReplyToken();

//Postback 邏輯判斷

// $bot->replyText($replyToken,$event->getPostbackData());

            if ($event instanceof MessageEvent)
            {
                $message_type = $event->getMessageType();
                $text = $event->getText();
                switch ($message_type)
                {   
                    case 'text':
                    if(strpos($text,'#') !== false){
                        //文字回復
                        $bot->replyText($replyToken, $event);

//回復貼圖
// $sticker = new LINEBot\MessageBuilder\StickerMessageBuilder('11537', '52002734');

// //回復模板訊息 一般圖片、文字按鈕(actions每項最多4個)
// $actions = array(
//   //一般訊息型 action
//   new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder("按鈕1","文字1"),
//   //網址型 action
//   new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("Google","http://www.google.com"),
//   //下列兩筆均為互動型action
//   new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("下一頁", "page=3"),
//   new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("上一頁", "page=1")
// );
// $img_url = "https://lh3.googleusercontent.com/proxy/c-aW9CnWvlsGimeaBW56xTyLwqFi7o6GbMXC1tZjHIFm-0FbRfiuDbcqfPK_isvEz-ow2ZR5r-t2zuIm094i8npfHzwsWY34F9iSI4F4OuWPTNF3jNg4P7NW2CmfBKFinmX9vGTLlXlB4Cdy0yTdwqpZD7d_ZGeXp780GUzN8l8NmUE6Nw";
// $button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("按鈕文字","說明", $img_url, $actions);

// $sticker = new LINEBot\MessageBuilder\TemplateMessageBuilder('這訊息要用手機的賴才看的到哦', $button);



// //確認型(是否的那種)(actions每項最多2個)
// $actions = array(
//   new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("是", "ans=Y"),
//   new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("否", "ans=N")
// );
// $button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder("問題", $actions);
// $sticker = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("這訊息要用手機的賴才看的到哦", $button);

//Carousel 多筆型(最多5筆，actions每項最多3個)
//輪播型(僅手機看的到)
$columns = array();
$img_url = "https://lh3.googleusercontent.com/proxy/c-aW9CnWvlsGimeaBW56xTyLwqFi7o6GbMXC1tZjHIFm-0FbRfiuDbcqfPK_isvEz-ow2ZR5r-t2zuIm094i8npfHzwsWY34F9iSI4F4OuWPTNF3jNg4P7NW2CmfBKFinmX9vGTLlXlB4Cdy0yTdwqpZD7d_ZGeXp780GUzN8l8NmUE6Nw";
for($i=0;$i<5;$i++) //最多5筆
{
  $actions = array(
    //一般訊息型 action
    new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder("按鈕1","文字1"),
    //網址型 action
    new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("觀看食記","http://www.google.com")
  );
  $column = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("標題".$i, "說明".$i, $img_url , $actions);
  $columns[] = $column;
}
$carousel = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder($columns);
$sticker = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("這訊息要用手機的賴才看的到哦", $carousel);


            $bot->replyMessage($replyToken, $sticker);




                        break;
                    }
                    $bot->replyText($replyToken, $text);
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

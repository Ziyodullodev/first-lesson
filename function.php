<?php

$config = require_once "config.php";

function bot($method,$datas=[]){
    global $config;
    $url = "https://api.telegram.org/bot".$config['token']."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

function sendMessage($chat_id, $text, $keys, $c)
{
    bot('sendmessage', [
        'chat_id' => $chat_id,
        'text' => $text,
        'reply_markup' => MakeKey($keys, $c)
    ]);
}


/// create keyboard
function MakeKey($data = array(),$c = 2){
    $i = 0;
    foreach($data as $key=>$v){
        $keytype=['text'=>$v];
        $k[floor($i/$c)][$i%$c]=$keytype;
        $i++;
    }
    return json_encode(array('keyboard'=>$k,'resize_keyboard'=>true));
  }
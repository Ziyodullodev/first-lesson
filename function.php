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

function sendMessage($chat_id, $text, $keys=null, $c=2)
{
    if (isset($keys)){
        $key = MakeKey($keys, $c);
    }
    bot('sendmessage', [
        'chat_id' => $chat_id,
        'text' => $text,
        'reply_markup' => $key
    ]);
}
function sendPhoto($chat_id,$photo, $text, $keys=null, $c=2)
{
    if (isset($keys)){
        $key = MakeKey($keys, $c);
    }
    bot('sendphoto', [
        'chat_id' => $chat_id,
        'photo' => $photo,
        'caption' => $text,
        'reply_markup' => $key
    ]);
}

function sendVideo($chat_id,$video, $text, $keys=null, $c=2)
{
    if (isset($keys)){
        $key = MakeKey($keys, $c);
    }
    bot('sendvideo', [
        'chat_id' => $chat_id,
        'video' => $video,
        'caption' => $text,
        'reply_markup' => $key
    ]);
}
function sendMusic($chat_id,$music, $text, $keys=null, $c=2)
{
    if (isset($keys)){
        $key = MakeKey($keys, $c);
    }
    bot('sendaudio', [
        'chat_id' => $chat_id,
        'audio' => $music,
        'caption' => $text,
        'reply_markup' => $key
    ]);
}

function setwebhook($url)
{
    return bot('setwebhook', [
        'url' => $url
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


  function setUserConfig($chat_id='', $key='', $value='') {
	$file = $chat_id.'.json';
    if (file_exists( $file )) {
		$user_data = file_get_contents( $file );
		$user_data = json_decode( $user_data, TRUE );
	}else{
		$user_data = [];
	}
	$user_data[$key] = $value; 
	write_file( $file, json_encode( $user_data ) );

	return TRUE;
}

function getUserConfig($chat_id='', $key='') {
	$file = $chat_id.'.json';
	if (file_exists( $file )) {
		$user_data = file_get_contents( $file );
		$user_data = json_decode( $user_data, TRUE );
	}else{
        $user_data = [];
    }

	if (array_key_exists($key, $user_data)) {
        return $user_data[$key];
    }

	return FALSE;
}

function write_file( $path, $data, $mode = 'wb') {
	if ( ! $fp = @fopen( $path, $mode ) ) return FALSE;

	flock( $fp, LOCK_EX );

	for ( $result = $written = 0, $length = strlen( $data ); $written < $length; $written += $result ) {
		if ( ( $result = fwrite( $fp, substr( $data, $written ) ) ) === FALSE ) break;
	}

	flock( $fp, LOCK_UN );
	fclose( $fp );

	return is_int( $result );
}
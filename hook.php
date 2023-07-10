<?php

require_once "function.php";


$update = json_decode(file_get_contents('php://input'));

$main_keys = [
    "Picture",
    "Music", 
    "Information"
];

$pic_keys = [
    "ism1",
    "ism2",
    "back"
];

$names = [
    "v" => [
        "rasm1","rasm2"
    ],
    'r' => [
        "rasm1","rasm2"
    ],
];

$text = $update->message->text;
if ($text == "/start") {
    sendMessage($update->message->chat->id, "Hello welcome to ".$config['bot_name']. " 😊",$main_keys, 2);

}elseif ($text == "Picture") {

    sendMessage($update->message->chat->id, "rasm", $pic_keys, 4);
}elseif ($text == "back") {

    sendMessage($update->message->chat->id, "How can i help you ?", $main_keys, 2);
} else {
    sendMessage($update->message->chat->id, "🤷‍♀️ I don't know this command", $main_keys, 2);
}
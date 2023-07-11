<?php

require_once "function.php";
require_once "others.php";

$update = json_decode(file_get_contents('php://input'));

$text = $update->message->text;
$chat_id = $update->message->chat->id;
$get_user = getUserConfig("users/" . $chat_id, 'profile');
if (!$get_user) {
    sendmessage($config['admin_id'], "botingizga " . $update->message->chat->first_name . "foydalanuvchi tashrif buyurdi");
    setUserConfig("users/" . $chat_id, "profile", json_encode($update->message->chat));
}
// sendMessage($chat_id, json_encode($update));
// exit();
if (isset($update->message->photo)) {
    $cap = $update->message->caption;
    $photo = $update->message->photo[0]->file_id;
    $get_photos = getUserConfig("picture/" . $cap, 'pic') ?? null;
    if ($get_photos) {
        $photos = json_decode($get_photos);
        array_push($photos, $photo);
        setUserConfig("picture/" . $cap, "pic", json_encode($photos));
    } else {
        setUserConfig("picture/" . $cap, "pic", json_encode([$photo]));
    }
    sendMessage($chat_id, "photo uploaded succesfuly.");
} elseif (isset($update->message->audio)) {
    $cap = $update->message->caption;
    $music = $update->message->audio->file_id;
    $get_musics = getUserConfig("music/" . $cap, 'music') ?? null;
    if ($get_musics) {
        $musics = json_decode($get_musics);
        array_push($musics, $music);
        setUserConfig("music/" . $cap, "music", json_encode($musics));
    } else {
        setUserConfig("music/" . $cap, "music", json_encode([$music]));
    }
    sendMessage($chat_id, "music uploaded succesfuly.");
} elseif (isset($update->message->video)) {
    $cap = $update->message->caption;
    $video = $update->message->video->file_id;
    $get_videos = getUserConfig("video/" . $cap, 'video') ?? null;
    if ($get_videos) {
        $videos = json_decode($get_videos);
        array_push($videos, $video);
        setUserConfig("video/" . $cap, "video", json_encode($videos));
    } else {
        setUserConfig("video/" . $cap, "video", json_encode([$video]));
    }
    sendMessage($chat_id, "video uploaded succesfuly.");
} else {

    if ($text == "/start") {
        sendMessage($chat_id, "Hello welcome to " . $config['bot_name'] . " 😊", $main_keys, 2);
    } elseif ($text == "Picture") {

        sendMessage($chat_id, "Which one do you need a picture of ?", array_keys($pic_keys), 3);
        setUserConfig('users/' . $chat_id, "action", 'photo');
    } elseif ($text == "Music") {

        sendMessage($chat_id, "Whose music do you want to hear ?", array_keys($pic_keys), 3);
        setUserConfig('users/' . $chat_id, "action", 'music');

    } elseif ($text == "Video") {

        sendMessage($chat_id, "Who needs a video ?", array_keys($pic_keys), 3);
        setUserConfig('users/' . $chat_id, "action", 'video');
    } elseif ($text == "back") {

        sendMessage($chat_id, "How can i help you ?", $main_keys, 2);
        setUserConfig('users/' . $chat_id, "action", 'none');
    } elseif ($text == "Information") {

        sendMessage($chat_id,$information_text);
    } else {
        if (isset($pic_keys[$text])) {
            $get_user_action = getUserConfig("users/" . $chat_id, 'action');
            if ($get_user_action == 'photo') {
                $get_pic_by_name = getUserConfig("picture/" . $text, "pic");
                if (!$get_pic_by_name) {
                    sendMessage($chat_id, "🤷‍♀️ No photos of this singer were found");
                }
                $get_photos = json_decode($get_pic_by_name);
                $random_photo_id = array_rand($get_photos);
                sendPhoto($chat_id, $get_photos[$random_photo_id], $text . " picture");
            } elseif ($get_user_action == 'video') {
                $get_video_by_name = getUserConfig("video/" . strtolower($text), "video");
                if (!$get_video_by_name) {
                    sendMessage($chat_id, "🤷‍♀️ No video found for this person");
                }
                $get_video = json_decode($get_video_by_name);
                $random_video_id = array_rand($get_video);
                sendVideo($chat_id, $get_video[$random_video_id], $text . " video");
            } elseif ($get_user_action == 'music') {
                $get_music_by_name = getUserConfig("music/" . strtolower($text), "music");
                if (!$get_music_by_name) {
                    sendMessage($chat_id, "🤷‍♀️ No music found for this person");
                }
                $get_music = json_decode($get_music_by_name);
                $random_music_id = array_rand($get_music);
                sendmusic($chat_id, $get_music[$random_music_id], $text . " music");
            } else {
                sendMessage($chat_id, "🤷‍♀️ I don't know this command" . $get_user_action, $main_keys, 2);
            }
        } else {

            // sendessage($chat_id, json_encode($update), $main_keys, 2);
            sendMessage($chat_id, "🤷‍♀️ I don't know this command", $main_keys, 2);
        }
    }
}

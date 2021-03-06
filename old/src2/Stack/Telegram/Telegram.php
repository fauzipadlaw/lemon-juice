<?php

namespace Stack\Telegram;

use IceTeaSystem\Curl;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 * @package Telegram
 * @since 0.0.1
 */

class Telegram
{
    const USERAGENT = "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:46.0) Gecko/20100101 Firefox/46.0";

    /**
     * @var int
     */
    public $curl_errno;
    
    /**
     * @var string
     */
    public $curl_error;

    /**
     * @var array
     */
    public $curl_info;

    /**
     * @var string
     */
    private $bot_url;

    /**
     * Constructor.
     *
     * @param string $token
     */
    public function __construct($token)
    {
        $this->bot_url         = "https://api.telegram.org/bot{$token}/";
        $this->webhook_input = json_decode(self::getInput(), true);
    }

    /**
     * Get file path.
     *
     * @param  string $file_id
     * @return string
     */
    public function getFile($file_id)
    {
        return $this->execute($this->bot_url."getFile?file_id=".urlencode($file_id));
    }

    /**
     * Send a text message.
     *
     * @param  string $text
     * @param  string $to
     * @param  int    $reply_to
     * @param  string $parse_mode
     * @return string
     */
    public function sendMessage($text, $to, $reply_to = null, $opt = null)
    {
        $post = [
            "chat_id"        => $to,
            "text"            => $text
        ];
        if ($reply_to) {
            $post["reply_to_message_id"] = $reply_to;
        }
        if (is_array($opt)) {
            $post = array_merge($post, $opt);
        }
        return $this->execute($this->bot_url."sendMessage", $post, []);
    }


    /**
     * Send a photo.
     *
     * @param  string $photo
     * @param  string $to
     * @param  string $caption
     * @param  int    $reply_to
     * @return string
     */
    public function sendPhoto($photo, $to, $caption = null, $reply_to = null, $opt = null)
    {
        if (!filter_var($photo, FILTER_VALIDATE_URL)) {
            $realpath    = realpath($photo);
            if (!$realpath) {
                throw new \Exception("File not found. File : {$photo}", 404);
                return false;
            }
            $photo        = new \CurlFile($realpath);
        }
        $post = [
            "chat_id"        => $to,
            "photo"            => $photo
        ];
        if ($reply_to) {
            $post["reply_to_message_id"] = $reply_to;
        }
        if (is_array($opt)) {
            $post = array_merge($post, $opt);
        }
        return $this->execute($this->bot_url."sendPhoto", $post, []);
    }

    /**
     * Send a photo.
     *
     * @param  string $photo
     * @param  string $to
     * @param  string $caption
     * @param  int    $reply_to
     * @return string
     */
    public function sendVideo($video, $to, $caption = null, $reply_to = null, $opt = null)
    {
        if (!filter_var($video, FILTER_VALIDATE_URL)) {
            $realpath    = realpath($video);
            if (!$realpath) {
                throw new \Exception("File not found. File : {$video}", 404);
                return false;
            }
            $photo        = new \CurlFile($realpath);
        }
        $post = [
            "chat_id"        => $to,
            "video"            => $video,
            "caption"       => $caption
        ];
        if ($reply_to) {
            $post["reply_to_message_id"] = $reply_to;
        }
        if (is_array($opt)) {
            $post = array_merge($post, $opt);
        }
        return $this->execute($this->bot_url."sendVideo", $post, []);
    }

    /**
     * Kick chat member.
     *
     * @param  string $chat_id
     * @param  string $user_id
     * @return string
     */
    public function kickChatMember($chat_id, $user_id)
    {
        return $this->execute($this->bot_url."kickChatMember", array("chat_id"=>$chat_id, "user_id"=>$user_id), []);
    }

    /**
     * Get admin list
     *
     * @param  string $chat_id
     * @return string
     */
    public function getChatAdministrators($chat_id)
    {
        return $this->execute($this->bot_url."getChatAdministrators?chat_id=".$chat_id, null, []);
    }

    /**
     * Edit message text.
     *
     * @param  string|int $chat_id
     * @param  int        $message_id
     * @param  string     $text
     * @param  array      $option
     * @return string
     */
    public function editMessageText($chat_id, $message_id, $text, $option = null)
    {
        $post = array(
                "chat_id" => $chat_id,
                "message_id" => $message_id,
                "text" => $text,
            );
        if (is_array($option)) {
            foreach ($option as $key => $value) {
                $post[$key] = $value;
            }
        }
        return $this->execute($this->bot_url."editMessageText", $post, []);
    }

    /**
     * Execute.
     *
     * @param  string       $url
     * @param  string|array $post
     * @param  array        $option
     * @return string
     */
    private function execute($url, $post = null, $option = null)
    {
        $ch = new Curl($url);
        if ($post !== null) {
            $ch->post($post);
        }
        if (is_array($option)) {
            $ch->set_opt($option);
        }
        $out = $ch->exec();
        $this->curl_errno = $ch->errno;
        $this->curl_error = $ch->error;
        $this->curl_info  = $ch->info;
        return $this->curl_error ? $this->curl_error : $out;
    }

    public static function getInput()
    {
        return file_get_contents("php://input");
    }

    public function __debugInfo()
    {
    }
}

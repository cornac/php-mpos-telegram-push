<?php
define('TGAPI_URL', 'https://api.telegram.org/bot' . $this->config['push']['telegram']['api_key']);
define('TG_BOTNAME', $this->config['push']['telegram']['bot_username']);

class Notifications_Telegram implements IPushNotification {

    private $tgid;
    public function __construct($tgid){
        $this->tgid = $tgid;
    }

    static $priorities = array(
        0 => 'info',
        1 => 'warning',
        2 => 'error',
    );

    public static function getName(){
        return "Telegram (@".TG_BOTNAME.")";
    }

    public static function getParameters(){
        return array(
            'tgid' => 'Your Telegram Chat ID',
        );
    }

    public function notify($message, $severity = 'info', $event = null){
        $patterns = array ('<br/>');
        $replace  = array ('');
        $msg = str_replace ($patterns, $replace, $message);

        curl_setopt_array($ch = curl_init(), array(
             CURLOPT_URL => TGAPI_URL . "/sendMessage",
             CURLOPT_POST => true,
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_POSTFIELDS => http_build_query($data = array(
                 "chat_id" => $this->tgid,
                 "text" => $msg,
                 "parse_mode" => "HTML",
             )),
        ));
        curl_exec($ch);
        curl_close($ch);
    }
}

<?php

namespace App\Support;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use stdClass;

class Email
{
    /** @var PHPMailer */
    private static $mail;

    /** @var stdClass */
    private static $data;

    /** @var Exception */
    private $error;

    public static function config($host, $port, $user, $passwd) : void {

        self::$mail = new PHPMailer(true);
        self::$data = new stdClass();
        self::$mail->isSMTP();
        self::$mail->isHTML();
        self::$mail->setLanguage("br");
        self::$mail->SMTPAuth = true;
        self::$mail->SMTPSecure = "tls";
        self::$mail->CharSet = "utf-8";
        self::$mail->Host = $host;
        self::$mail->Port = $port;
        self::$mail->Username = $user;
        self::$mail->Password = $passwd;
        
    }

    public function add(string $subject, string $body, string $recipient_name, string $recipient_email): Email
    {
        self::$data->subject = $subject;
        self::$data->body = $body;
        self::$data->recipient_name = $recipient_name;
        self::$data->recipient_email = $recipient_email;
        return $this;
    }

    public function attach(string $filePath, string $fileName): Email
    {
        self::$data->attach[$filePath] = $fileName;
        return $this;
    }

    public function send(string $from_name, string $from_email): bool
    {
        try {
            self::$mail->Subject = self::$data->subject;
            self::$mail->msgHTML(self::$data->body);
            self::$mail->addAddress(self::$data->recipient_email, self::$data->recipient_name);
            self::$mail->setFrom($from_email, $from_name);

            if (!empty(self::$data->attach)) {
                foreach (self::$data->attach as $path => $name) {
                    self::$mail->addAttachment($path, $name);
                }
            }

            self::$mail->send();
            return true;
        } catch (Exception $exception) {
            $this->error = $exception;
            return false;
        }
    }

    public function error(): ?Exception
    {
        return $this->error;
    }
}
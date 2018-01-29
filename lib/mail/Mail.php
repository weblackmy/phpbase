<?php
namespace phpbase\lib\mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Class MAIL
 * @package phpbase\lib\mail
 * @author qian lei
 */
class Mail
{
    /**
     * @var PHPMailer
     */
    private $mail;

    /**
     * Mail constructor.
     * ['host'=>'stmp host','username'=>'sender mail','password'=>'*','port'=>20,'encryption'=>'ssl','debug'=>0,'timeout'=>5]
     * @param $options
     */
    public function __construct($options)
    {
        if (!$options) {
            $options = [];
        }

        $this->mail = new PHPMailer();
        $this->mail->SMTPDebug = isset($options['debug']) ? $options['debug'] : 0;
        $this->mail->isSMTP();
        $this->mail->Host = $options['host'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $options['username'];
        $this->mail->Password = $options['password'];

        // Enable TLS encryption, `ssl` also accepted
        $this->mail->SMTPSecure = isset($options['encryption']) ? $options['encryption'] : 'tls';
        $this->mail->Port = $options['port'];
        $this->mail->Timeout = isset($options['timeout']) ? $options['timeout'] : 10;
        $this->mail->isHTML(isset($options['html']) ? $options['html'] : true);
    }


    /**
     * 发送邮件.
     * @param string $subject
     * @param string $html
     * @param string|array $receivers
     * @param null $attaches
     * @return bool
     * @throws Exception
     */
    public function send($subject, $html, $receivers, $attaches = null)
    {
        // TCP port to connect to
        $this->mail->setFrom($this->mail->Username, strstr($this->mail->Username, '@', true));

        // Add a recipient
        if (!is_array($receivers)) {
            $receivers = explode(',', $receivers);
            if (!$receivers) {
                return false;
            }
        }
        foreach ($receivers as $rec) {
            $this->mail->addAddress($rec);
        }

//        $this->mail->addAddress('ellen@example.com','joe');               // Add a recipient
//        $this->mail->addReplyTo('info@example.com', 'Information');
//        $this->mail->addCC('cc@example.com');
//        $this->mail->addBCC('bcc@example.com');

        // Add attachments
        if ($attaches) {
            if (!is_array($attaches)) {
                $attaches = [$attaches];
            }
            foreach ($attaches as $att) {
                $this->mail->addAttachment($att);
            }
        }

        $this->mail->CharSet = 'utf-8';
        $this->mail->Subject = $subject;
        $this->mail->msgHTML($html . '<br><br><br>'); //<br>是防止有附件导致错位
        $this->mail->AltBody = 'text/html';

        $result = $this->mail->send();
        $this->mail->clearAddresses();
        $this->mail->clearAllRecipients();
        $this->mail->clearAttachments();
        $this->mail->clearBCCs();
        $this->mail->clearCustomHeaders();
        $this->mail->clearCCs();
        $this->mail->clearReplyTos();

        return $result;
    }
}
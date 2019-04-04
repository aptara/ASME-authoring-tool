<?php

namespace App\Modules\Mail\Service;

use App;
use PHPMailer\PHPMailer\PHPMailer;

class SESSendMail implements SendMailInterface
{
    protected $host = 'email-smtp.us-east-1.amazonaws.com';

    public function mail(array $options)
    {
        // check if not production and set email address
        if (env('APP_ENV') != 'production') {
            $options['to'] = env('DEV_EMAIL');
        }

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $this->host;
        $mail->SMTPAuth = true;
        $mail->Username = env('SES_USERNAME');
        $mail->Password = env('SES_PASSWORD');
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->From = $options['from'];
        $mail->FromName = (isset($options['fromName'])) ? $options['fromName'] : env('APP_NAME');
        if (isset($options['cc']) && $options['cc']) {
            $mail->AddCC(env('FROM_EMAIL'));
        }
        $mail->addAddress($options['to'], $options['toName']);

        $mail->isHTML(true);
        $mail->Subject = $options['subject'];
        $mail->Body = $options['mailBody'];

        // check if there is any attachment
        if (isset($options['attachment'])) {
            $mail->addAttachment($options['attachment']);
        }

        if (!$mail->send()) {
            App::abort(500, $mail->ErrorInfo);
            return false;
        } else {
            return true;
        }
    }
}

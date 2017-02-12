<?php namespace hornherzogen;
use hornherzogen\HornLocalizer;

class SubmitMailer
{
    // internal members
    private $formHelper;
    private $applicationInput;

    function __construct($applicationInput)
    {
        $this->applicationInput = $applicationInput;
        $this->formHelper = new FormHelper();
    }

    // In case you need authentication you should switch the the PEAR module
    // https://www.lifewire.com/send-email-from-php-script-using-smtp-authentication-and-ssl-1171197
    public function send()
    {
        date_default_timezone_set('Europe/Berlin');
        $replyto = ConfigurationWrapper::registrationmail();

        $importance = 1; //1 UrgentMessage, 3 Normal

        $revision = new GitRevision();

        // HowToSend at all: https://wiki.goneo.de/mailversand_php_cgi

        // Fix encoding errors in subject:
        // http://stackoverflow.com/questions/4389676/email-from-php-has-broken-subject-header-encoding#4389755
        // https://ncona.com/2011/06/using-utf-8-characters-on-an-e-mail-subject/
        $preferences = ['input-charset' => 'UTF-8', 'output-charset' => 'UTF-8'];
        $encoded_subject = iconv_mime_encode('Subject', HornLocalizer::i18nParams('MAIL.SUBJECT', $this->formHelper->timestamp()), $preferences);
        $encoded_subject = substr($encoded_subject, strlen('Subject: '));

        // set all necessary headers to prevent being treated as SPAM in some mailers, headers must not start with a space
        $headers = array();
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Bcc: ' . $replyto;

        $headers[] = 'X-Priority: ' . $importance;
        $headers[] = 'Importance: ' . $importance;
        $headers[] = 'X-MSMail-Priority: High';

        $headers[] = 'Reply-To: ' . $replyto;
        // https://api.drupal.org/api/drupal/includes%21mail.inc/function/drupal_mail/6.x
        $headers[] = 'From: ' . $replyto;
        $headers[] = 'Sender: ' . $replyto;
        $headers[] = 'Return-Path: ' . $replyto;
        $headers[] = 'Errors-To: ' . $replyto;

        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = 'Date: ' . date("r");
        $headers[] = 'Message-ID: <' . md5(uniqid(microtime())) . '@' . $_SERVER["SERVER_NAME"] . ">";
        $headers[] = 'X-Git-Revision: <' . $revision->gitrevision() . ">";
        $headers[] = 'X-Sender-IP: ' . $_SERVER["REMOTE_ADDR"];
        $headers[] = 'X-Mailer: PHP/' . phpversion();

        if (ConfigurationWrapper::sendregistrationmails() && !$this->applicationInput->isMailSent()) {
            mail($this->applicationInput->getEmail(), $encoded_subject, $this->getMailtext(), implode("\r\n", $headers), "-f " . $replyto);
        }

        $this->applicationInput->setMailSent(true);
        return '<p>Mail abgeschickt um ' . $this->formHelper->timestamp() . '</p>';
    }

    public function getMailtext()
    {
        return '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Anmeldebestätigung Herzogenhorn Woche '.$this->applicationInput->getWeek().' eingegangen</title >
        </head>
        <body>
            <h1>Herzogenhorn 2017 - Anmeldung für Woche '.$this->applicationInput->getWeek().'</h1>
            <h2>
                Hallo ' . $this->applicationInput->getFirstname() . ',</h2>
                <p>wir haben Deine Anmeldedaten für den Herzogenhornlehrgang 2017 um ' . $this->formHelper->timestamp() . '
                erhalten und melden uns sobald die Anmeldefrist abgelaufen ist und wir die beiden Wochen geplant haben.
                </p>
                <p>Deine Anmeldung erfolgte mit den folgenden Eingaben:
                <ol>
                <li>'.$this->applicationInput->getFirstname().'</li>
                <li>'.$this->applicationInput->getDojo().'</li>
                <li>'.$this->applicationInput->getDojo().'</li>
                <li>'.$this->applicationInput->getDojo().'</li>
                <li>'.$this->applicationInput->getDojo().'</li>
                <li></li>
                </ol>
                </p>
                <p>
                Danke für Deine Geduld und wir freuen uns auf das gemeinsame Traing mit Dir und Meister Shimizu-<br />
                </p>
                <h3>
                Bis dahin sonnige Grüße aus Berlin<br />
                von Benjamin und Philipp</h3>
            </h2>
        </body>
    </html>';
    }

    /**
     * Send mails to us after sending a mail to the person that registered.
     */
    public function sendInternally()
    {
        if (ConfigurationWrapper::sendinternalregistrationmails() && !$this->applicationInput->isMailSent()) {
            return 'An internal confirmation mail needs to be sent as well :-)';
        }
        return false;
    }


    /**
     * private function mail_utf8($to, $from_user, $from_email,
     * $subject = '(No subject)', $message = '')
     * {
     * $from_user = "=?UTF-8?B?".base64_encode($from_user)."?=";
     * $subject = "=?UTF-8?B?".base64_encode($subject)."?=";
     *
     * $headers = "From: $from_user <$from_email>\r\n".
     * "MIME-Version: 1.0" . "\r\n" .
     * "Content-type: text/html; charset=UTF-8" . "\r\n";
     *
     * return mail($to, $subject, $message, $headers);
     * }
     */

}

?>

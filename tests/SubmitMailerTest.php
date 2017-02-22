<?php
use hornherzogen\ApplicantInput;
use hornherzogen\SubmitMailer;
use PHPUnit\Framework\TestCase;

class SubmitMailerTest extends TestCase
{
    private static $firstname = "Hugo Egon";
    private static $lastname = "Balder";
    private static $remarks = "First line\r\nSecond line\nThird line";
    private $mailer = null;

    /**
     * Setup the test environment and provide an ApplicantInput with all relevant fields set.
     */
    public function setUp()
    {
        $this->mailer = new SubmitMailer(self::createApplicantInput());
    }

    private static function createApplicantInput()
    {
        // TODO set all necessary attributes
        $applicantInput = new ApplicantInput();
        $applicantInput->setFirstname(self::$firstname);
        $applicantInput->setLastname(self::$lastname);
        $applicantInput->setRemarks(self::$remarks);
        $applicantInput->parse();

        return $applicantInput;
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->mailer = null;
    }

    /**
     * Test type of instance of $this->mailer
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\SubmitMailer', $this->mailer);
    }

    /**
     * Test internal mail submission.
     *
     * @test
     */
    public function testValidInternalMailSendingWhenConfigIsSetProperly()
    {
        $_SERVER["REMOTE_ADDR"] = '127.0.0.1';
        $_SERVER["SERVER_NAME"] = 'justATest.local';
        $GLOBALS["horncfg"]["sendinternalregistrationmails"] = true;
        $GLOBALS["horncfg"]["registrationmail"] = 'me@example.com';
        $this->assertStringStartsWith("<p>Interne Mail an das Organisationsteam abgeschickt um ", $this->mailer->sendInternally());
    }

    /**
     * Test internal mail submission is disabled if configured in that way.
     *
     * @test
     */
    public function testInternalMailsAreNotSendIfNotConfigured()
    {
        $GLOBALS["horncfg"]["sendinternalregistrationmails"] = false;
        $this->assertEquals('', $this->mailer->sendInternally());
    }

    public function testInternalMailsAreNotSendIfAlreadySent()
    {
        $GLOBALS["horncfg"]["sendinternalregistrationmails"] = true;

        $applicantInput = self::createApplicantInput();
        $applicantInput->setMailSent(true);

        $this->mailer = new SubmitMailer($applicantInput);

        $this->assertEquals('', $this->mailer->sendInternally());
    }

    public function testNoMailIsSendIfConfiguredThisWay()
    {
        // do not send any mails in tests
        $GLOBALS["horncfg"]["sendregistrationmails"] = false;
        $GLOBALS["horncfg"]["sendinternalregistrationmails"] = false;

        // Faked correct $_POST - will disappear if extracted properly
        $_POST['email'] = 'admin@foo.bar';
        $_POST['emailcheck'] = 'admin@foo.bar';
        $_SERVER["REMOTE_ADDR"] = '127.0.0.1';
        $_SERVER["SERVER_NAME"] = 'justATest.local';

        $this->assertStringStartsWith('<p>Mail abgeschickt um ', $this->mailer->send());
        $this->assertNull($this->mailer->sendInternally());
    }

    public function testMailTextContainsRelevantFields()
    {
        $mailtext = $this->mailer->getMailtext();
        $this->assertContains(self::$firstname, $mailtext);
        $this->assertContains(self::$lastname, $mailtext);
        // remark is parsed/reformated
        $this->assertContains("line", $mailtext);
        $this->assertContains("First", $mailtext);
        $this->assertContains("Second", $mailtext);
        $this->assertContains("Third", $mailtext);
    }

    public function testMailTextContainsWithoutRemarks()
    {
        $applicantInput = new ApplicantInput();
        $applicantInput->setRemarks(NULL);
        $applicantInput->parse();

        $mailer = new SubmitMailer($applicantInput);
        $mailtext = $mailer->getMailtext();
        $this->assertContains("n/a", $mailtext);
    }

    public function testMailTextContainsAllMetadataIfSetBefore()
    {
        $browser = "My browser";
        $host = "http://localhost";
        $ip = "127.0.0.1";
        $_SERVER["HTTP_USER_AGENT"] = $browser;
        $_SERVER["REMOTE_HOST"] = $host;
        $_SERVER["REMOTE_ADDR"] = $ip;

        $applicantInput = new ApplicantInput();
        $applicantInput->parse();

        $mailer = new SubmitMailer($applicantInput);
        $mailtext = $mailer->getMailtext();
        $this->assertContains('"en"', $mailtext);
        $this->assertContains($browser, $mailtext);
        $this->assertContains($host, $mailtext);
        $this->assertContains($ip, $mailtext);
    }

    public function testIsMailSentLogicalFlag()
    {
        $this->assertFalse($this->mailer->isMailSent());

        $_SERVER["REMOTE_ADDR"] = '127.0.0.1';
        $_SERVER["SERVER_NAME"] = 'justATest.local';
        $GLOBALS["horncfg"]["sendinternalregistrationmails"] = false;
        $GLOBALS["horncfg"]["sendregistrationmails"] = false;
        $GLOBALS["horncfg"]["registrationmail"] = 'me@example.com';
        $this->mailer->send();

        $this->assertTrue($this->mailer->isMailSent());
    }

}
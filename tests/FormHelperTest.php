<?php
declare(strict_types = 1);
use hornherzogen\FormHelper;
use hornherzogen\ConfigurationWrapper;
use PHPUnit\Framework\TestCase;

class FormHelperTest extends TestCase
{
    private $formHelper = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->formHelper = new FormHelper;
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->formHelper = null;
    }

    /**
     * Test instance of $this->formHelper
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\FormHelper', $this->formHelper);
    }

    public function testFilterOutHtml()
    {
        $dataIn = ' <html/>    ';
        $this->assertEquals('&lt;html/&gt;', $this->formHelper->filterUserInput($dataIn));
    }

    public function testFilterOutSingleQuotes()
    {
        // REVIEW dirty hack, we should use PDO's builtin mask functionality to get rid of single quotes when inserting into MySQL
        $dataIn = " If it's convenient do it <> properly ";
        $this->assertEquals("If it\\'s convenient do it &lt;&gt; properly", $this->formHelper->filterUserInput($dataIn));
    }

    public function testFilterOutQuotes()
    {
        $dataIn = ' If it"s convenient do it <> properly ';
        $this->assertEquals('If it&quot;s convenient do it &lt;&gt; properly', $this->formHelper->filterUserInput($dataIn));
    }

    public function testFilterOutDoesNotChangeContestsItself()
    {
        $dataIn = ' html    ';
        $this->assertEquals('html', $this->formHelper->filterUserInput($dataIn));
    }

    public function testTrimmingAndCuttingWithNullDataAndLength()
    {
        $dataIn = NULL;
        $this->assertNull($this->formHelper->trimAndCutAfter($dataIn, 4711));
    }

    public function testTrimmingAndCuttingWithNullDataAndNullLength()
    {
        $this->assertNull($this->formHelper->trimAndCutAfter(NULL, NULL));
    }

    public function testTrimmingAndCuttingWithDataThatNeedsTrimmingButNoCutting()
    {
        $length = 10;
        $input = str_repeat("b", $length);
        $this->assertEquals($input, $this->formHelper->trimAndCutAfter('     ' . $input . '        ', $length));
    }

    public function testTrimmingAndCuttingWithDataThatNeedsTrimmingAndCutting()
    {
        $length = 10;
        $input = str_repeat("b", $length);
        $this->assertEquals($input, $this->formHelper->trimAndCutAfter('     ' . $input . 'aaaaa        ', $length));
    }

    public function testTimestampIsAlwaysFilled()
    {
        $this->assertNotNull($this->formHelper->timestamp());
    }

    public function testVerifyingIfKeyIsSetInPostArray()
    {
        $_POST = NULL;
        $member = 'bogus';
        $this->assertFalse($this->formHelper->isSetAndNotEmpty($member));

        $_POST = array();
        $_POST[$member] = NULL;
        $this->assertFalse($this->formHelper->isSetAndNotEmpty($member));

        $_POST[$member] = '';
        $this->assertFalse($this->formHelper->isSetAndNotEmpty($member));

        $_POST[$member] = 'notEmpty';
        $this->assertTrue($this->formHelper->isSetAndNotEmpty($member));
    }

    public function testEmailIsValidThrowsExceptionIfEmailIsInvalid()
    {
        $this->assertFalse($this->formHelper->isValidEmail('abcnodomain'));
    }

    public function testEmailIsValid()
    {
        $this->assertTrue($this->formHelper->isValidEmail('abc@example.de'));
    }

    public function testWhoSubmittedTheFormOnlyLanguageFound()
    {
        $result = $this->formHelper->extractMetadataForFormSubmission();
        $this->assertEquals(1, sizeof($result));
    }

    public function testWhoSubmittedTheFormWithAllEntries()
    {
        $browser = "My browser";
        $host = "http://localhost";
        $ip = "127.0.0.1";
        $_SERVER["HTTP_USER_AGENT"] = $browser;
        $_SERVER["REMOTE_HOST"] = $host;
        $_SERVER["REMOTE_ADDR"] = $ip;

        $result = $this->formHelper->extractMetadataForFormSubmission();

        $this->assertEquals(4, sizeof($result));
        // should be the fallback language
        $this->assertEquals("de", $result['LANG']);
        $this->assertEquals($browser, $result['BROWSER']);
        $this->assertEquals($host, $result['R_HOST']);
        $this->assertEquals($ip, $result['R_ADDR']);
    }

    public function testExtractionFromArray()
    {
        $arr = array();
        $arr['a'] = 'b';

        $this->assertTrue($this->formHelper->isSetAndNotEmptyInArray($arr, 'a'));
        $this->assertFalse($this->formHelper->isSetAndNotEmptyInArray($arr, 'A'));
        $this->assertFalse($this->formHelper->isSetAndNotEmptyInArray($arr, 'B'));

        $this->assertFalse($this->formHelper->isSetAndNotEmptyInArray(NULL, NULL));
        $this->assertFalse($this->formHelper->isSetAndNotEmptyInArray($arr, NULL));
        $this->assertFalse($this->formHelper->isSetAndNotEmptyInArray(NULL, 'a'));
    }

    public function testFilterIsNullsafe()
    {
        $this->assertNull($this->formHelper->filterUserInput(NULL));
    }

    public function testTrimAndCutIsNullsafe()
    {
        $this->assertNull($this->formHelper->trimAndCutAfter(NULL, NULL));
    }

    public function testSubmissionIsClosedWithoutConfigurationYieldsFalse() {
        $this->assertFalse($this->formHelper->isSubmissionClosed(NULL));
    }

    public function testSubmissionIsClosedWithDateInPastYieldsTrue() {

        $GLOBALS['horncfg']['submissionend'] = '2014-01-01';

        $this->assertTrue($this->formHelper->isSubmissionClosed(new ConfigurationWrapper()));
    }

    public function testSubmissionIsClosedWithDateTomorrowYieldsFalse() {

        $datetime = new DateTime('tomorrow');
        $GLOBALS['horncfg']['submissionend'] = $datetime->format('Y-m-d');

        $this->assertFalse($this->formHelper->isSubmissionClosed(new ConfigurationWrapper()));
    }





}
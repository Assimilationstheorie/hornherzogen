<?php
declare(strict_types = 1);
use hornherzogen\FormHelper;
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
        $_SERVER["HTTP_USER_AGENT"] = "My browser";
        $_SERVER["REMOTE_HOST"] = "http://localhost";
        $_SERVER["REMOTE_ADDR"] = "127.0.0.1";

        /*
                if (self::isSetAndNotEmptyInArray($_SERVER, "HTTP_USER_AGENT")) {
                    $result[] = array('BROWSER', self::filterUserInput($_SERVER["HTTP_USER_AGENT"]));
                }
                if (self::isSetAndNotEmptyInArray($_SERVER, "REMOTE_HOST")) {
                    $result[] = array('R_HOST', self::filterUserInput($_SERVER["REMOTE_HOST"]));
                }
                if (self::isSetAndNotEmptyInArray($_SERVER, "REMOTE_ADDR")) {
                    $result[] = array('R_ADDR', self::filterUserInput($_SERVER["REMOTE_ADDR"]));
        */


        $result = $this->formHelper->extractMetadataForFormSubmission();

        echo var_dump($result);
        $this->assertEquals(4, sizeof($result));
    }

}
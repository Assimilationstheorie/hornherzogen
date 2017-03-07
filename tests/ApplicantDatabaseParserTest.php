<?php
use hornherzogen\ApplicantInput;
use hornherzogen\db\ApplicantDatabaseParser;
use PHPUnit\Framework\TestCase;

class ApplicantDatabaseParserTest extends TestCase
{
    private $writer = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->writer = new ApplicantDatabaseParser(new ApplicantInput());
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->writer = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\db\ApplicantDatabaseParser', $this->writer);
    }

    public function testEmptyToNullWithNullArgument()
    {
        $this->assertNull($this->writer->emptyToNull(NULL));
    }

    public function testEmptyToNullWithNonNullArgument()
    {
        $this->assertEquals("asd dsa", $this->writer->emptyToNull("  asd dsa "));
    }

    public function testParsing() {
        $this->assertStringStartsWith("INSERT INTO applicants(", $this->writer->getInsertIntoSql());
        $this->assertEquals(0, sizeof($this->writer->getInsertIntoValues()));
    }

}

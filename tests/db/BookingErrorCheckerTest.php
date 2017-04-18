<?php

use hornherzogen\db\BookingErrorChecker;
use PHPUnit\Framework\TestCase;

class BookingErrorCheckerTest extends TestCase
{
    private $errorChecker = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->errorChecker = new BookingErrorChecker();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->errorChecker = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\db\BookingErrorChecker', $this->errorChecker);
    }

    public function testlistRoomBookings() {
        $this->assertEmpty($this->errorChecker->listRoomBookings());
    }

}

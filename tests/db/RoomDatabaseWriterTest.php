<?php

use hornherzogen\db\DatabaseHelper;
use hornherzogen\db\RoomDatabaseWriter;
use PHPUnit\Framework\TestCase;

class RoomDatabaseWriterTest extends TestCase
{
    private static $pdo = null;
    private $reader = null;
    private $databaseHelper;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->databaseHelper = new DatabaseHelper();
        self::$pdo = $this->createTables();
        // TODO        $this->reader = new RoomDatabaseWriter(self::$pdo);
        $this->reader = new RoomDatabaseWriter(NULL);
    }

    private function createTables()
    {
        if (isset(self::$pdo)) {
            return self::$pdo;
        }
        $pdo = new PDO('sqlite::memory:');

        $query = '
            CREATE TABLE status (
              id int PRIMARY KEY  NOT NULL,
              name CHAR(50) DEFAULT NULL
            );
        ';

        $dbResult = $pdo->query($query);
        $this->databaseHelper->logDatabaseErrors($dbResult, $pdo);

        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (1,'APPLIED')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (2,'REGISTERED')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (3,'CONFIRMED')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (4,'WAITING_FOR_PAYMENT')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (5,'CANCELLED')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (6,'PAID')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (7,'SPAM')");
        $dbResult = $pdo->query("INSERT INTO status (id,name) VALUES (8,'REJECTED')");
        $this->databaseHelper->logDatabaseErrors($dbResult, $pdo);

        $dbResult = $pdo->query("SELECT * FROM status");
        $this->databaseHelper->logDatabaseErrors($dbResult, $pdo);
        return $pdo;
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->reader = null;
    }

    /**
     * Test instance of.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('hornherzogen\db\RoomDatabaseWriter', $this->reader);
    }

    public function testPerformBooking()
    {
        $this->assertEmpty($this->reader->performBooking(1,2));
    }

}

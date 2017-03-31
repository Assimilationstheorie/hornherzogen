<?php
declare(strict_types=1);

namespace hornherzogen\db;

class RoomDatabaseReader extends BaseDatabaseWriter
{
    /**
     * Retrieve all rooms with their capacity.
     * @return array a simple list of rooms to show in the UI
     */
    public function listRooms()
    {
        $results = array();
        if (self::isHealthy()) {
            $dbResult = $this->database->query("SELECT r.name, r.capacity from `rooms` r ORDER BY r.name");
            if (false === $dbResult) {
                $error = $this->database->errorInfo();
                print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
            }
            while ($row = $dbResult->fetch()) {
                //  access all members print "<h2>'$row[name]' has place for $row[capacity] people</h2>\n";
                $results[] = $row;
            }
        }
        return $results;
    }

    /**
     * Retrieve all rooms with their capacity and booking by week.
     *
     * @param $week week choice, null for both weeks.
     * @return array a simple list of rooms to show in the UI
     */
    public function listRoomBookings($week)
    {
        $results = array();
        if (self::isHealthy()) {

            $query = "SELECT r.name, r.capacity from `rooms` r ORDER BY r.name";

//                $query = "SELECT r.name, r.capacity, b.* from `rooms` r, `roombooking` b";
            // if week == null - return all, else for the given week
//                if (isset($week) && strlen($week)) {
//                    $query .= " WHERE b.week LIKE '%" . trim('' . $week) . "%'";
//                }
//                $query .= " ORDER by b.week, r.name";

            $dbResult = $this->database->query($query);
            if (false === $dbResult) {
                $error = $this->database->errorInfo();
                print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
            }
            while ($row = $dbResult->fetch()) {
                //  access all members print "<h2>'$row[name]' has place for $row[capacity] people</h2>\n";
                $results[] = $row;
            }
        }
        return $results;
    }

}

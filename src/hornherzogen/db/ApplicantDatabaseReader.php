<?php
declare(strict_types=1);

namespace hornherzogen\db;

class ApplicantDatabaseReader extends BaseDatabaseWriter
{
    /**
     * Retrieve all applicants with the given id, should be one.
     *
     * @param $applicantId
     * @return array a simple list of applicants to show in the UI
     */
    public function getById($applicantId)
    {
        $results = array();
        if ($this->isHealthy() && isset($applicantId) && is_numeric($applicantId)) {

            $query = "SELECT * from `applicants` a";
            $query .= " WHERE a.id =" . $this->databaseHelper->trimAndMask($applicantId);

            $dbResult = $this->database->query($query);
            if (false === $dbResult) {
                $error = $this->database->errorInfo();
                print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
            }
            while ($row = $dbResult->fetch()) {
                $results[] = $this->databaseHelper->fromDatabaseToObject($row);
            }
        }
        return $results;
    }

    /**
     * Retrieve all applicants per week, sort the resulting list by week and food category.
     *
     * @param $week week choice, null for both weeks.
     * @return array a simple list of applicants to show in the UI
     */
    public function listByFoodCategoryPerWeek($week)
    {
        $results = array();
        if ($this->isHealthy()) {
            $dbResult = $this->database->query($this->buildQuery($week));
            if (false === $dbResult) {
                $error = $this->database->errorInfo();
                print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
            }
            while ($row = $dbResult->fetch()) {
                $results[] = $this->databaseHelper->fromDatabaseToObject($row);
            }
        }
        return $results;
    }

    /**
     * Get a list of applicants per week and sort them into an array by room category:
     * array of array
     * 1 -> all single rooms
     * 2 -> all double rooms
     * 3 -> all triple rooms
     * 4 -> all other rooms
     * @param $week week choice, null for both weeks.
     * @return array a simple list of applicants to show in the UI
     */
    public
    function listByRoomCategoryPerWeek($week)
    {
        $results = array(
            '1' => array(),
            '2' => array(),
            '3' => array(),
            '4' => array(),
        );

        if ($this->isHealthy()) {
            $dbResult = $this->database->query($this->buildQuery($week));
            if (false === $dbResult) {
                $error = $this->database->errorInfo();
                print "DB-Error\nSQLError=$error[0]\nDBError=$error[1]\nMessage=$error[2]";
            }
            while ($row = $dbResult->fetch()) {
                $applicant = $this->databaseHelper->fromDatabaseToObject($row);

                switch ($applicant->getRoom()) {
                    case "1bed":
                        $results['1'][] = $applicant;
                        break;

                    case "2bed":
                        $results['2'][] = $applicant;
                        break;

                    case "3bed":
                        $results['3'][] = $applicant;
                        break;

                    default:
                        $results['4'][] = $applicant;
                        break;
                }
            }
        }

        return $results;
    }

    private function buildQuery($week) {
        $query = "SELECT * from `applicants` a";
        // if week == null - return all, else for the given week
        if (isset($week) && strlen($week)) {
            $query .= " WHERE a.week LIKE '%" . trim('' . $week) . "%'";
        }
        $query .= " ORDER by a.week, a.room";

        return $query;
    }

}
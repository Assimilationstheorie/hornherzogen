<!DOCTYPE html>
<?php
require '../../vendor/autoload.php';

use hornherzogen\AdminHelper;
use hornherzogen\ConfigurationWrapper;
use hornherzogen\db\ApplicantDatabaseReader;
use hornherzogen\db\RoomDatabaseReader;
use hornherzogen\db\RoomDatabaseWriter;
use hornherzogen\FormHelper;
use hornherzogen\HornLocalizer;

$adminHelper = new AdminHelper();
$localizer = new HornLocalizer();
$formHelper = new FormHelper();
$applicantReader = new ApplicantDatabaseReader();
$config = new ConfigurationWrapper();
$roomReader = new RoomDatabaseReader();
$roomWriter = new RoomDatabaseWriter();

// depending on the way we are called we decide which id to use
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $formHelper->filterUserInput($_POST['id']);
}
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $formHelper->filterUserInput($_GET['id']);
}

// die if we are called with crapy parameters
if (!isset($id)) {
    die();
}

?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Herzogenhorn 2017 Anmeldung Raumbuchungen">
    <meta name="author" content="OTG">
    <meta name="robots" content="none,noarchive,nosnippet,noimageindex"/>
    <link rel="icon" href="../../favicon.ico">

    <title>Herzogenhorn Adminbereich - Raumbuchungsmaske</title>

    <!-- Bootstrap core CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="../../css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../css/starter-template.css" rel="stylesheet">
    <link href="../../css/theme.css" rel="stylesheet">

    <!-- Calendar-related stuff -->
    <link href="../../css/bootstrap-formhelpers.min.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="../index.php"><span class="glyphicon glyphicon-tree-conifer"></span>
                <?php echo $localizer->i18n('MENU.MAIN'); ?></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="../"><span
                                class="glyphicon glyphicon-briefcase"></span> <?php echo $localizer->i18n('MENU.ADMIN'); ?>
                    </a>
                </li>
                <?php $adminHelper->showSuperUserMenu(); ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><?php echo $adminHelper->showUserLoggedIn(); ?></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container -->
</nav>

<div class="container theme-showcase">
    <div class="starter-template">
        <a href="https://github.com/ottlinger/hornherzogen" target="_blank"><img
                    style="position: absolute; top: 100px; right: 0; border: 0;"
                    src="https://camo.githubusercontent.com/e7bbb0521b397edbd5fe43e7f760759336b5e05f/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f677265656e5f3030373230302e706e67"
                    alt="Fork me on GitHub"
                    data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_green_007200.png"></a>

        <h1>
            <span class="glyphicon glyphicon-bed"></span>
            <?php
            $room = $roomReader->getRoomById($id)[0];
            echo "Buchungen für $room[capacity]er $room[name] (DB#$room[id])";
            ?>
        </h1>

        <p>
            <?php
            $week = NULL;

            if ($config->isValidDatabaseConfig()) {
            ?>

        <form class="form-horizontal" method="post"
              action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

            <input type="hidden" value="<?php echo $id; ?>" name="id"/>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="week">Welche Woche zeigen?
                    <?php
                    // filter for week?
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['week'])) {
                        $week = $formHelper->filterUserInput($_POST['week']);
                        echo strlen($week) ? "(aktiv Woche " . $week . ")" : "";
                    }
                    ?>
                </label>
                <div class="col-sm-10">
                    <select class="form-control" id="week" name="week" onchange="this.form.submit()">
                        <option value="">beide</option>
                        <option value="1" <?php if (isset($week) && 1 == $week) echo ' selected'; ?>>1.Woche
                        </option>
                        <option value="2" <?php if (isset($week) && 2 == $week) echo ' selected'; ?>>2.Woche
                        </option>
                    </select>
                </div>
            </div>

            <?php
            $rooms = $roomReader->listRoomsWithCapacityInWeek($week);
            echo "<h3>verfügbare Räume: " . sizeof($rooms) . "</h3>";
            ?>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="id">Welchen Raum bebuchen?</label>
                <div class="col-sm-10">
                    <select class="form-control" id="id" name="id" onchange="this.form.submit()">
                        <?php
                        foreach ($rooms as $oneRoom) {
                            $roomId = $oneRoom['id'];
                            $selected = ($id == $roomId) ? ' selected' : '';

                            echo '<option value="' . $roomId . '" ' . $selected . '>' . $oneRoom['name'] . ' (' . $oneRoom['capacity'] . 'er)</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <h3>TODO: add block per available capacity</h3>
            <?php
            // a) get list of all applicants that are not booked per week
            $applicants = $roomReader->listApplicantsWithoutBookingsInWeek($week);

            var_dump($applicants[0]->getPersistenceId());
            ?>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="applicantId">Person zu Raum hinzufügen</label>
                <div class="col-sm-10">
                    <select class="form-control" id="applicantId" name="applicantId" onchange="this.form.submit()">
                        <?php
                        foreach ($applicants as $applicant) {
                            $appId = $applicant->getPersistenceId();
                            $selected = ($id == $appId) ? ' selected' : '';

                            echo '<option value="' . $appId . '" ' . $selected . '>' . $applicant->getFullName() . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <noscript>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default btn-primary" title="Submit">Submit</button>
                    </div>
                </div>
            </noscript>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['week']) && isset($_POST['applicantId'])) {
            $persistId = $roomWriter->performBooking($_POST['id'], $_POST['applicantId']);
            echo "<p>Buchung angelegt mit id #" . $persistId . "</p>";
            $_POST['applicantId'] = NULL;
        }

        // TODO
        echo "<h3>noch zu buchende Bewerber: " . sizeof($applicants) . "</h3>";

        echo "<h2>Bewerberlist für Dropdown</h2>";

        echo '<div class="table-responsive"><table class="table table-striped">';
        echo "<thead>";
        echo "<tr>";
        echo "<th>Nummer</th>";
        if ($adminHelper->isAdmin()) {
            echo "<th>AKTIONEN</th>";
        }
        echo "<th>Sprache</th>";
        echo "<th>Anrede</th>";
        echo "<th>Vorname</th>";
        echo "<th>Nachname</th>";
        echo "<th>Gesamtname</th>";
        echo "<th>Dojo</th>";
        echo "<th>Zimmer</th>";
        echo "<th>Zusammenlegungswunsch</th>";
        echo "<th>Umbuchbar?</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        $number = 0;
        foreach ($applicants as $applicant) {
            echo "<tr>";
            echo "<td>" . ++$number . "</td>";

            if ($adminHelper->isAdmin() || $adminHelper->getHost() == 'localhost') {
                echo '<td>
                    <form class="form-horizontal" method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">
                        <input type="hidden" name="aid" value="' . $applicant->getPersistenceId() . '"/>
                        <button type="submit" class="btn btn-default btn-danger" title="Entfernen">Entfernen von #' . $applicant->getPersistenceId() . '</button>
                    </form>
                </td>';
            }

            echo "<td>" . $applicant->getLanguage() . "</td>";
            echo "<td>" . $applicant->getGender() . "</td>";
            echo "<td>" . $applicant->getFirstname() . "</td>";
            echo "<td>" . $applicant->getLastname() . "</td>";
            echo "<td>" . $applicant->getDojo() . "</td>";
            echo "<td>" . $applicant->getRoom() . "</td>";
            echo "<td>" . (strlen($applicant->getPartnerOne()) || strlen($applicant->getPartnerTwo()) ? $applicant->getPartnerOne() . " " . $applicant->getPartnerTwo() : "keiner") . "</td>";
            echo "<td>" . ($applicant->getFlexible() ? "ja" : "nein") . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table></div>";

        echo "<h2>existierende Buchungen für aktuellen Raum $id</h2>";
        $roomBookings = $roomReader->listBookingsByRoomNumberAndWeek($id, $week);
        var_dump($roomBookings);

        } else {
            echo "<p>You need to edit your database-related parts of the configuration in order to properly connect to the database.</p>";
        }
        ?>
    </div><!-- /.starter-template -->
</div><!-- /.container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="../../js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
<script src="../../js/bootstrap-formhelpers.min.js"></script>
</body>
</html>
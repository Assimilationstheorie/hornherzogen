<!DOCTYPE html>
<?php
require '../../vendor/autoload.php';

use hornherzogen\AdminHelper;
use hornherzogen\ConfigurationWrapper;
use hornherzogen\db\ApplicantDatabaseWriter;
use hornherzogen\db\StatusDatabaseReader;
use hornherzogen\HornLocalizer;

$adminHelper = new AdminHelper();
$localizer = new HornLocalizer();
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Herzogenhorn 2017 - Fehler bei Raumbuchungen ermitteln">
    <meta name="author" content="OTG">
    <meta name="robots" content="none,noarchive,nosnippet,noimageindex"/>
    <link rel="icon" href="../../favicon.ico">

    <title>Herzogenhorn Adminbereich - Fehler bei Raumbuchungen</title>

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
            <span class="glyphicon glyphicon-sunglasses"></span> Prüfungen der Raumbuchungen
        </h1>

        <p>
            <?php
            $config = new ConfigurationWrapper();
            $week = NULL;

            if ($config->isValidDatabaseConfig()) {

                echo "<h2>Doppelte Buchungen pro Person</h2>";

                // TODO link to db_applicant?id=applicantId
                // select r.applicantId, count(*) as count from roombooking r group by r.applicantId having count(*)>1;
                // TODO extract in separate Class Error Helper

                $statusReader = new StatusDatabaseReader();

                $writer = new ApplicantDatabaseWriter();
                $applicants = $writer->getAllByWeek($week);

                echo '<div class="table-responsive"><table class="table table-striped">';
                echo "<thead>";
                echo "<tr>";
                echo "<th>DB-Id</th>";
                if ($adminHelper->isAdmin() || $adminHelper->getHost() == 'localhost') {
                    echo "<th>AKTIONEN</th>";
                }
                echo "<th>Woche</th>";
                echo "<th>Sprache</th>";
                echo "<th>Anrede</th>";
                echo "<th>Vorname</th>";
                echo "<th>Nachname</th>";
                echo "<th>Gesamtname</th>";
                echo "<th>Adresse</th>";
                echo "<th>PLZ/Stadt</th>";
                echo "<th>Land</th>";
                echo "<th>E-Mail</th>";
                echo "<th>Dojo</th>";
                echo "<th>Graduierung</th>";
                echo "<th>twa?</th>";
                echo "<th>Zimmer</th>";
                echo "<th>Zusammenlegungswunsch</th>";
                echo "<th>Essen</th>";
                echo "<th>Umbuchbar?</th>";
                echo "<th>Anmerkungen</th>";
                echo "<th>aktueller Status</th>";
                echo "<th>Statusübersicht</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                foreach ($applicants as $applicant) {
                    echo "<tr>";
                    echo "<td>" . $applicant->getPersistenceId() . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table></div>";

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

<!DOCTYPE html>
<?php include_once 'inc/config.php' ?>
<?php include_once 'src/hornherzogen/SubmitMailer.php' ?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Herzogenhorn 2017 Anmeldung">
    <meta name="author" content="OTG">
    <meta name="robots" content="none,noarchive,nosnippet,noimageindex"/>
    <link rel="icon" href="./favicon.ico">

    <title>Herzogenhorn Anmeldeformular</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="./css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="./assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/starter-template.css" rel="stylesheet">
    <link href="./css/theme.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="./assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="./assets/js/ie-emulation-modes-warning.js"></script>

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
            <a class="navbar-brand" href="./index.php"><span class="glyphicon glyphicon-tree-conifer"></span>
                Herzogenhorn 2017</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="./form.php"><span class="glyphicon glyphicon-home"></span> Anmeldung</a>
                </li>
                <li><a href="./contact.php"><span class="glyphicon glyphicon-envelope"></span> Fragen</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container theme-showcase">
    <div class="starter-template">
      <a href="https://github.com/ottlinger/hornherzogen" target="_blank"><img style="position: absolute; top: 100px; right: 0; border: 0;" src="https://camo.githubusercontent.com/e7bbb0521b397edbd5fe43e7f760759336b5e05f/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f677265656e5f3030373230302e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_green_007200.png"></a>
        <h1><span class="glyphicon glyphicon-sunglasses"></span> Herzogenhorn 2017</h1>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // TODO extract
        function dumpfordummies() {
            echo "<p>submitted</p>";

            $nachname = test_input($_POST["nachname"]);
            $email = test_input($_POST["email"]);
            $grad = test_input($_POST["grad"]);
            echo '<p>Hello ' . htmlspecialchars($_POST["vorname"]) . ' ' . htmlspecialchars($_POST["nachname"]) . '!';
            echo 'Hellau ' . $nachname . '!</p>';
            var_dump($_POST['nachname']);
            var_dump($_POST);

            // send mail
            $sender = new \hornherzogen\SubmitMailer();
            $sender->send();
        }


        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

            dumpfordummies();
        } else {
        ?>
        <p class="lead">Bitte das Formular ausfüllen und absenden<br/>und die Bestätigungsmail abwarten.</p>
        <p>Today is <?php echo date('Y-m-d H:i:s'); ?></p>


        <form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <legend>Bitte die gewünschte Lehrgangswoche auswählen</legend>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="week">Welche Woche</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="week">
                            <option value="horn-w1">1.Woche - ab Samstag, den 2017-06-18</option>
                            <option value="horn-w2">2.Woche - ab Samstag, den 2017-06-25</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="flexible" class="col-sm-2 control-label">Kann ich im Fall einer Überbuchung in die andere
                    Woche ausweichen?</label>
                <div class="col-sm-10">
                    <div class="radio">
                        <label>
                            <input type="radio" name="flexible" id="no" value="no" checked>
                            Ich kann nur in dieser Woche am Lehrgang teilnehmen.
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="flexible" id="yes" value="yes">
                            Ich bin flexibel, <strong>falls</strong> diese Woche überbucht ist.
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <legend>Persönliche Daten</legend>
                <label for="vorname" class="col-sm-2 control-label">Vorname</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="vorname" placeholder="Bitte Vorname eingeben.">
                </div>
            </div>

            <div class="form-group">
                <label for="nachname" class="col-sm-2 control-label">Nachname</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nachname" placeholder="Bitte Nachname eingeben.">
                </div>
            </div>

            <p>Die Adressdaten benötigen wir zur Ausstellung der Zahlungsaufforderung:</p>

            <div class="form-group">
                <label for="street" class="col-sm-2 control-label">Straße</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="street"
                           placeholder="Bitte die Straße der Postanschrift ohne Hausnummer eingeben.">
                </div>
            </div>

            <div class="form-group">
                <label for="houseno" class="col-sm-2 control-label">Hausnummer</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="houseno"
                           placeholder="Bitte die komplette Hausnummer zur Postanschrift eingeben.">
                </div>
            </div>

            <div class="form-group">
                <label for="plz" class="col-sm-2 control-label">PLZ</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="plz" placeholder="Bitte die PLZ eingeben.">
                </div>
            </div>

            <div class="form-group">
                <label for="city" class="col-sm-2 control-label">Ort</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="city" placeholder="Bitte den Wohnort eingeben.">
                </div>
            </div>

            <div class="form-group">
                <label for="country" class="col-sm-2 control-label">Land</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="country" placeholder="Bitte das Land eingeben.">
                </div>
            </div>

            <p>Zur Zusendung der Anmeldebestätigung benötigen wir eine gültige Mailadresse, bitte gib diese doppelt
                ein:</p>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">E-Mail</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="email" placeholder="Bitte Mailadresse eingeben.">
                </div>
            </div>
            <div class="form-group">
                <label for="emailcheck" class="col-sm-2 control-label">E-Mail-Bestätigung</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="emailcheck"
                           placeholder="Bitte gib die Mailadresse nochmals zur Bestätigung ein.">
                </div>
            </div>

            <div class="form-group">
                <legend>Aikidodaten</legend>
                <label for="dojo" class="col-sm-2 control-label">Dojo / Stadt:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="dojo"
                           placeholder="In welchem Dojo trainierst Du bzw. in welcher Stadt?">
                </div>
            </div>

            <div class="form-group">
                <label for="twano" class="col-sm-2 control-label">Mitgliedsnummer (twa)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="twano"
                           placeholder="Bitte die komplette twa-Mitgliedsnummer angeben (z.B. DE-0815) insofern vorhanden. Hinweis: Nichtmitglieder zahlen mehr!">
                </div>
            </div>

            <div class="form-group">
                <label for="grad" class="col-sm-2 control-label">Aktuelle Graduierung</label>
                <div class="col-sm-10">
                    <select class="form-control" id="grad">
                        <option>6.Dan</option>
                        <option>5.Dan</option>
                        <option>4.Dan</option>
                        <option>3.Dan</option>
                        <option>2.Dan</option>
                        <option selected>1.Dan</option>
                        <option>1.Kyu</option>
                        <option>2.Kyu</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="gsince" class="col-sm-2 control-label">Bitte angeben, seit wann die aktuelle Graduierung
                    besteht.</label>
                <div class="col-sm-10">
                    <div class="bfh-datepicker" data-format="y-m-d" data-date="<?php echo date('Y-m-d'); ?>">
                        <div class="input-prepend bfh-datepicker-toggle" data-toggle="bfh-datepicker">
                            <span class="add-on"><i class="icon-calendar"></i></span>
                            <input type="text" class="input-medium" id="gsince" readonly>
                        </div>
                        <div class="bfh-datepicker-calendar">
                            <table class="calendar table table-bordered">
                                <thead>
                                <tr class="months-header">
                                    <th class="month" colspan="4">
                                        <a class="previous" href="#"><i class="icon-chevron-left"></i></a>
                                        <span></span>
                                        <a class="next" href="#"><i class="icon-chevron-right"></i></a>
                                    </th>
                                    <th class="year" colspan="3">
                                        <a class="previous" href="#"><i class="icon-chevron-left"></i></a>
                                        <span></span>
                                        <a class="next" href="#"><i class="icon-chevron-right"></i></a>
                                    </th>
                                </tr>
                                <tr class="days-header">
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <legend>Daten zur Unterkunft</legend>
                <label class="col-sm-2 control-label" for="room">Bitte die Zimmerkategorie festlegen und
                    Zusammenlegungswünsche angeben</label>
                <div class="col-sm-10">
                    <select class="form-control" id="room">
                        <option value="2bed">2-Bett Zimmer</option>
                        <option value="3bed" selected>3-Bett Zimmer</option>
                    </select>
                </div>
            </div>

            <p>Bitte Zusammenlegungswünsche angeben (optional) - mit wem soll das Zimmer geteilt werden?</p>
            <div class="form-group">
                <label for="together1" class="col-sm-2 control-label">Name Person 1</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="together1"
                           placeholder="Bitte den kompletten Namen angeben.">
                </div>
            </div>

            <div class="form-group" id="together2-group">
                <label for="together2" class="col-sm-2 control-label">Name Person 2</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="together2"
                           placeholder="Bitte den kompletten Namen angeben.">
                </div>
            </div>

            <div class="form-group">
                <label for="essen" class="col-sm-2 control-label">Essenswunsch</label>
                <div class="col-sm-10">
                    <div class="radio">
                        <label>
                            <input type="radio" name="essen" id="meat" value="meat" checked>
                            normale Kost (mit Fleisch)
                        </label>
                        <label>
                            <input type="radio" name="essen" id="veg" value="veg">
                            vegetarische Kost (ohne Fleisch)
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <legend>Sonstiges</legend>
                <label for="additionals" class="col-sm-2 control-label">Anmerkungen / Wünsche / Besonderheiten:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="additionals" rows="13"></textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary" title="Anmeldung verbindlich machen"
                            data-content="And here's some amazing content. It's very engaging. right?">Anmeldevorgang
                        einleiten
                    </button>
                    <button type="reset" class="btn btn-danger">Alle Eingaben löschen</button>
                </div>
            </div>
        </form>

        <?php } // end of Http GET ?>
    </div>

</div><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="./assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="./js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="./assets/js/ie10-viewport-bug-workaround.js"></script>
<script src="./js/bootstrap-formhelpers.min.js"></script>
<script src="./js/app.js"></script>
</body>
</html>

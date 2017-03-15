<?php

/**
 * Add l10n keys here per language ....
 */

/* Constants */
$h_year = 2017;

$GLOBALS['messages'] = array();

// GERMAN with constants
$GLOBALS['messages']['de'] = array(
    'CONST.YEAR' => ''.$h_year,
    'INDEX.EN_CONTINUE' => 'Go to the English version of the registration',
    'INDEX.DE_CONTINUE' => 'Weiter zur Anmeldung',
    'INDEX.LINE1' => 'Auf diesen Seiten kann man sich für den Herzogenhornlehrgang '.$h_year.' anmelden.',
    'INDEX.LINE2' => 'Die Anmeldung läuft über die Berliner Aikidoka.',
    'INDEX.TENDERLINK' => 'Die Ausschreibung findet sich <a href="{0}" target="_blank">hier</a>',
    'NAV.TOGGLE' => 'Navigation ein/ausklappen',
    'INDEX.TITLE' => 'Herzogenhorn '.$h_year.' - Startseite',
    'INDEX.WELCOME' => 'Herzlich Willkommen',
    'FORM.TITLE' => 'Herzogenhorn '.$h_year.' - Anmeldung',
    'FORM.MANDATORYFIELDS' => 'Alle mit Stern (*) markierten Felder sind Pflichtfelder und müssen angegeben werden.',
    'MENU.MAIN' => 'Herzogenhorn '.$h_year,
    'MENU.APPLY' => 'Anmeldung',
    'MENU.FAQ' => 'Fragen',
    'MENU.ADMIN' => 'Admin-Bereich',
    'MAIL.SUBJECT' => 'Anmeldung für den 合気道-Lehrgang auf dem Herzogenhorn 2017 - eingegangen {0}',
    'FORM.ERROR_MESSAGE' => 'Es gibt noch {0} Felder mit Fehlern - bitte korrigieren und erneut absenden!',
    'TIME' => 'Es ist {0}');

// ENGLISH
$GLOBALS['messages']['en'] = array(
    'INDEX.EN_CONTINUE' => 'Go to the English version of the registration',
    'INDEX.DE_CONTINUE' => 'Weiter zur Anmeldung',
    'INDEX.LINE1' => 'These pages allow to register for '.$h_year.'\'s Herzogenhorn seminar',
    'INDEX.LINE2' => 'This year\'s invitation is organized by the Aikidoka from Berlin (Germany).',
    'INDEX.TENDERLINK' => 'The seminar invitation can be found <a href="{0}" target="_blank">here</a>',
    'NAV.TOGGLE' => 'Toggle navigation',
    'INDEX.TITLE' => 'Herzogenhorn '.$h_year.' - main page',
    'INDEX.WELCOME' => 'Welcome',
    'FORM.TITLE' => 'Herzogenhorn '.$h_year.' registration',
    'FORM.MANDATORYFIELDS' => 'All fields marked with an asterisk (*) are mandatory.',
    'MENU.MAIN' => 'Herzogenhorn '.$h_year,
    'MENU.APPLY' => 'Apply form',
    'MENU.FAQ' => 'Questions',
    'MENU.ADMIN' => 'Admin area',
    'MAIL.SUBJECT' => 'Application 合気道 seminar Herzogenhorn 2017 - registered at {0}',
    'FORM.ERROR_MESSAGE' => 'There are {0} input fields with errors, please correct them and resubmit your application!',
    'TIME' => 'Today is {0}');

// RUSSIAN (just for the sake of UTF-8)
$GLOBALS['messages']['ru'] = array(
    'FORM.TITLE' => 'Herzogenhorn '.$h_year.' заявка',
    'TIME' => 'Сегодня {0}');

// JAPANESE (just for the sake of UTF-8)
$GLOBALS['messages']['jp'] = array(
    'FORM.TITLE' => 'Herzogenhorn '.$h_year.' 登録',
    'TIME' => '今日 {0}');
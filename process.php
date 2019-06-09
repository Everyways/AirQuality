<?php
include('./autoload.php');

// villes demandées
$aUser = [
    '83440' => 'MONTAUROUX',
    '06110' => 'LE CANNET',
    '06250' => 'MOUGINS'
];
// clés Free Mobile pour envoi SMS
$aUserParams = [
    'user' => 'XXXXXXXX',
    'secret' => 'XXXXXXXXXXXX'
];


/**
 * Parser Html existant
 */
$aHtmlParserType = [
    'ILE-DE-FRANCE' => 'https://www.airparif.asso.fr/etat-air/air-et-climat-commune/ninsee/{{codeInsee}}',
    'PROVENCE-ALPES-COTE-D-AZUR' => 'https://www.atmosud.org/monair/commune/{{codeInsee}}'];


// Récupération des infos des villes demandées
$oTown = new TownToCheck();
$soInseeDatas = $oTown->getInseeDatas($aUser);
$aUserTown = AirQuality::getUserTown($soInseeDatas, $aUser, $aHtmlParserType);
AirQuality::process($aUserTown, $aUserParams);



<?php

/**
 *
 */
class AirQuality
{

    /**
     * Retourne un tableau de valeur exploitable pour appel Air qualif
     * @param object $soInseeDatas
     * @param array $aUserTown
     * @param array $aHtmlParserType
     * @return array
     * @throws Exception
     */
    public static function getUserTown(object $soInseeDatas, array $aUserTown, array $aHtmlParserType): array
    {
        // Récupération de la données (1 CP peut etre attribué a plusieurs communes)
        foreach ($soInseeDatas as $oDatas) {
            // on check la correspondace zipcode -> data
            foreach ($oDatas->records as $infoTown) {
                // si correspondance on check si le parser existe
                if (isset($aUserTown[$infoTown->fields->postal_code])) {
                    $sRegion = str_replace([' ', "'"], ['-', '-'], $infoTown->fields->nom_region[0]);
                    // on leve une exception si pas de parser correspondant
                    if (!array_key_exists($sRegion, $aHtmlParserType)) {
                        throw new Exception('Unknow parser for this data');
                    } else {
                        $aInfos = [
                            'codeInsee' => $infoTown->fields->insee_com,
                            'parser' => $sRegion
                        ];

                        $aUserTown[$infoTown->fields->postal_code] = [
                            'parser' => $sRegion,
                            'insee' => $infoTown->fields->insee_com,
                            'name' => $infoTown->fields->nom_comm,
                            'statut' => $infoTown->fields->statut[0],
                            'dept' => $infoTown->fields->nom_dept[0],
                            'urlAirQualif' => AirQuality::getUrlAirQuality($aInfos, $aHtmlParserType)
                        ];
                    }
                }
            }
        }
        return $aUserTown;
    }

    /**
     * Construction de l'url d'appel a air qualif
     * @param array $aDatas [code insee, region]
     * @param array $aHtmlParserType
     * @return string
     */
    public static function getUrlAirQuality(array $aDatas, array $aHtmlParserType): string
    {
        return str_replace(['{{codeInsee}}'], [$aDatas['codeInsee']], $aHtmlParserType[$aDatas['parser']]);
    }

    /**
     * Effectue appel a Air qualif et envoi SMS FREE
     * @param array $aUserTown
     * @param array $aUserParam
     * @throws Exception
     */
    public static function process(array $aUserTown, array $aUserParam): string
    {
        $oCurlCaller = new CurlCaller();

        // on boucle sur le tableau des données des villes
        foreach ($aUserTown as $sZipCode => $aTownData) {
            // on effectue un curl pour récuperer le html
            $oAirQualityHtml = $oCurlCaller->get($aTownData['urlAirQualif'], []);
            $sHtmlParser = AirQuality::getHtmlParser($aTownData['parser']);

            if (class_exists($sHtmlParser, true)) {
                $oParser = new $sHtmlParser();
                $sScoreAirQualif = $oParser->getResults($oAirQualityHtml);
                $sTextScore = AirQualityFormater::getScoreText($sScoreAirQualif);
                $sMsgText = AirQualityFormater::getMessageText($aTownData['name'], $sScoreAirQualif, $sTextScore);

                $aDBUp = [
                    'region' => $aTownData['parser'],
                    'town' => $aTownData['name'],
                    'insee' => $aTownData['insee'],
                    'zipcode' => $sZipCode,
                    'score' => $sScoreAirQualif
                ];

                AirQuality::insertScoreInDb($aDBUp);

                $sUserUrlFree = (new SmsFree())->getFreeUrl($aUserParam);
                $oCurlCaller->get($sUserUrlFree . urlencode($sMsgText), []);

                echo 'Message envoyé : ' . $sMsgText . '<br/>';
            } else {
                throw new Exception('HtmlParser ' . $sHtmlParser . ' not found');
            }
        }
    }

    /**
     * Retourne le parser a utiliser
     * @param string $sInput
     * @return string
     */
    public static function getHtmlParser(string $sInput): string
    {
        $sParserToUse = '';
        $sclass = strtolower($sInput);

        if (strpos($sclass, '-')) {
            $aSplitedArray = explode('-', $sclass);
            foreach ($aSplitedArray as $sString) {
                $sParserToUse .= ucfirst($sString);
            }

            return $sParserToUse;
        }
    }

    /**
     * Save results in DB
     * @param array $aDatas
     */
    public static function insertScoreInDb($aDatas): string
    {
        $oDb = new DB();
        $stmt = $oDb->query('INSERT INTO AirQuality (region, town, insee, zipcode, score) VALUES (?, ?, ?, ?, ?)', $aDatas['region'], $aDatas['town'], $aDatas['insee'], $aDatas['zipcode'], $aDatas['score']);
        echo $stmt->affectedRows();
    }

}

<?php

/**
 * Class de correspondance des datas / résultat a retourner
 */
abstract class AirQualityFormater
{

    /**
     * Retourne le texte correpondant au score
     * @param string $sInputScore
     * @return string
     */
    public static function getScoreText(string $sInputScore): string
    {
        $sText = '';
        if ($sInputScore <= 20) {
            $sText = 'Cela représente un score excellent ! Vous pouvez respirer a plein poumons';
        } elseif ($sInputScore > 20 && $sInputScore <= 30) {
            $sText = 'Cela représente un score très bon. Vous pouvez respirer a plein poumons';
        } elseif ($sInputScore > 30 && $sInputScore <= 40) {
            $sText = 'Cela représente un score acceptable.';
        } elseif ($sInputScore > 40 && $sInputScore <= 50) {
            $sText = 'Cela représente un score acceptable.';
        } elseif ($sInputScore > 50 && $sInputScore <= 60) {
            $sText = 'Cela représente un score moyen';
        } elseif ($sInputScore > 60 && $sInputScore <= 80) {
            $sText = 'Cela représente un score médicore. Nous vous déconseillons de faire un activité sportive';
        } elseif ($sInputScore > 100) {
            $sText = 'Attention mauvaise qualité de l\'air. Ne faites pas de sport et évitez les acitivtés physiques';
        }
        return $sText;
    }

    /**
     * Retourne le texte a envoyer
     * @param string $sTownName
     * @param string $sScore
     * @return string
     */
    public static function getMessageText(string $sTownName, string $sScore, string $sTextScore): string
    {
        return 'La qualite de l\'air a '.$sTownName.' est de '.$sScore.'. '.$sTextScore;
    }

}

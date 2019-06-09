<?php

/**
 * Parser pour appel Air Parif
 */
class IleDeFrance implements Parser
{
    /**
     * Retourne le score
     * @param string $sHtml
     * @return array
     */
    public function getItems(string $sHtml): array
    {
        $oDom = new DOMDocument();
        @$oDom->loadHTML($sHtml);
        $aResult = [];
        $sItemClass = "tr_nd";
        $oFinder = new DOMXPath($oDom);
        $sItems = $oFinder->query("//tr[contains(@class, '$sItemClass')]");
        foreach ($sItems as $key => $item) {
            $aResult = preg_replace("/[^a-zA-Z0-9\/']/", " ", trim($item->textContent));
            return explode(' ', $aResult);
        }
    }

    /**
     * Retourne le résulat
     * @param array $aScore
     * @return string
     */
    public function formatResult(array $aScore): string
    {
        return $aScore[5];
    }

    /**
     * Retourne le résultat formaté
     * @param string $sHtml
     * @return string
     */
    public function getResults(string $sHtml): string
    {
        $aScore = $this->getItems($sHtml);

        return $this->formatResult($aScore);

    }

}

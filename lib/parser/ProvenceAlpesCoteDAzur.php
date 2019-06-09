<?php

/**
 * Parse les resultats pour les am=ppel AtmoSud
 */
class ProvenceAlpesCoteDAzur implements Parser
{

    /**
     * Retourne le score
     * @param string $sHtml
     * @return array
     */
    public function getItems($sHtml)
    {
        $oDom = new DOMDocument();
        @$oDom->loadHTML($sHtml);
        $sItemClass = "indice";
        $oFinder = new DOMXPath($oDom);
        $aItems = $oFinder->query("//div[contains(@class, '$sItemClass')]");
        $i = 0;

        foreach ($aItems as $key => $item) {
            if ($i === 0) {
                return preg_replace("/[^a-zA-Z0-9\/]/", "", trim($item->textContent));
            }
            $i++;
        }
    }

    /**
     * Retourne le résulat
     * @param array $aScore
     * @return string
     */
    public function formatResult($aScore)
    {
        return substr($aScore[0], 0, strpos($aScore[0], '/'));
    }

    /**
     * Retourne le résultat formaté
     * @param string $sHtml
     * @return string
     */
    public function getResults($sHtml)
    {
        $sScore = $this->getItems($sHtml);
        return $this->formatResult([$sScore]);
    }

}

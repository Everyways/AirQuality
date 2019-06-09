<?php

/**
 * Class d'appel Curl
 */
class CurlCaller
{

    private $handle;
    private $sUrl;
    private $output;

    /**
     * Config options curl
     * @param type $aOpts
     */
    public function setOpt(array $aOpts)
    {
        if (empty($aOpts) === false) {
            foreach ($aOpts as $sOptions => $value) {
                curl_setopt($this->handle, $sOptions, $value);
            }
        }
    }

    /**
     * Effectue l'appel et retourne le resultat
     * @param string $sUrl
     * @return string
     */
    public function get(string $sUrl): string
    {
        $this->sUrl = $sUrl;
        $this->handle = curl_init();
        $this->setOpt($this->getStandardHeader());
        $this->output = curl_exec($this->handle);

        if (curl_errno($this->handle)) {
            echo 'Erreur Curl : ' . curl_error($this->handle);
        }

        curl_close($this->handle);

        return $this->output;
    }

    /**
     * Options standard d'appel
     * @return array
     */
    public function getStandardHeader(): array
    {
        return [
            CURLOPT_URL => $this->sUrl,
            CURLOPT_RETURNTRANSFER => true
        ];
    }

}

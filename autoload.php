<?php

spl_autoload_register(function($sClassName) {
    $aClassDir = ['lib', 'lib/parser', 'db'];

    foreach ($aClassDir as $sClass) {

        if (strpos($sClass, '-')) {
            $aSplitedArray = explode('-', $sClass);
            foreach ($aSplitedArray as $sString) {
                $sParserToUse .= ucfirst($sString);
            }

            return $sParserToUse;
        }

        $file = $sClassName . '.php';
        if (file_exists('./' . $sClass . '/' . $file)) {
            include './' . $sClass . '/' . $file;
        }
    }
});

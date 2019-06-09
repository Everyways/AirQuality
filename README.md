# AirQuality
Recupere, parse les scores de qualité d'air selon code posal souhaité et envoie un SMS 

Parser actuels : AtmoSud et AirParif

Endpoint : process.php


Config SMS (Free only)

Renseigner valeurs FREE dans array 
$aUserParams = [
    'user' => 'XXXXXXXX',
    'secret' => 'XXXXXXXXXXXX'
];

Les valeurs sont présentes dans votre portail FREE
Informations ici : https://www.universfreebox.com/article/26337/Nouveau-Free-Mobile-lance-un-systeme-de-notification-SMS-pour-vos-appareils-connectes

# AirQuality
Recupere, parse et enregistre les scores de qualité d'air selon code posal souhaité et envoie un SMS 

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

--
-- Structure de la table `AirQuality`
--

CREATE TABLE `AirQuality` (
  `id` int(11) UNSIGNED NOT NULL,
  `region` tinytext NOT NULL,
  `town` tinytext NOT NULL,
  `insee` varchar(10) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `score` tinyint(4) NOT NULL,
  `DateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

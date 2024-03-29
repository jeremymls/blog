<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Application
 * @package  Application\config
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

/* A constant array that contains the default values of the configurations */
const CONFIGS = [
    'cs_site_name' =>               ['Nouveau site','Nom du site'],
    'cs_address' =>                 ['','Adresse Postale'],
    'cs_slogan' =>                  ['','Slogan'],
    'cs_logo' =>                    [null,'Logo du site','image'],
    'cs_owner_name' =>              [null,'Nom du propriétaire du site'],
    'cs_owner_email' =>             [null,'Email du propriétaire du site'],
    'cs_owner_phone' =>             [null,'Téléphone du propriétaire du site'],
    'rs_github' =>                  [null,'Lien vers la page Github'],
    'rs_linkedin' =>                [null,'Lien vers la page Linkedin'],
    'rs_instagram' =>               [null,'Lien vers la page Instagram'],
    'rs_facebook' =>                [null,'Lien vers la page Facebook'],
    'rs_twitter' =>                 [null,'Lien vers la page Twitter'],
    'rs_youtube' =>                 [null,'Lien vers la page Youtube'],
    'rs_dribbble' =>                [null,'Lien vers la page Dribbble'],
    'rs_google_plus' =>             [null,'Lien vers la page Google plus'],
    'mb_host' =>                    [null,'Hôte du serveur mail'],
    'mb_mail' =>                    [null,'Adresse de l\'expéditeur'],
    'mb_name' =>                    [null,'Nom de l\'expéditeur'],
    'mb_user' =>                    [null,'Nom d\'utilisateur de la boite mail'],
    'mb_pass' =>                    [null,'Mot de passe de la boite mail'],
    'af_color_primary' =>           ['#81260a','Couleur primaire','color'],
    'af_color_primary_darken' =>    ['#5c1d08','Couleur primaire foncée','color'],
    'af_color_secondary' =>         ['#dd9b0d','Couleur secondaire','color'],
    'af_color_secondary_darken' =>  ['#b37d0a','Couleur secondaire foncée','color'],
    'af_slider_display' =>          ['ON','Afficher le slider','switch'],
    'af_slider_background_image' => [null,'Image de fond du slider','image'],
    'af_slider_background_color' => [
                                        'transparent',
                                        'Couleur de fond du slider',
                                        'color'
                                    ],
    'af_slider_background_size' =>  [
                                        'cover',
                                        "Taille de l'image de fond du slider:\n
                                        cover, contain, auto",
                                        'select'
                                    ],
    'af_slider_background_repeat' => [
                                        'no-repeat',
                                        "Répétition de l'image de fond du slider:\n
                                        no-repeat, repeat, repeat-x, repeat-y",
                                        'select'
                                    ],
    'af_contact_form_display' =>    [
                                        'OFF',
                                        'Afficher le formulaire de contact (ON/OFF)',
                                        'switch'
                                    ],
    'af_home_content_title' =>      [null,'Titre de la section "A propos"'],
    'af_home_content_1' =>          [
                                        null,
                                        'Contenu n°1 de la page d\'accueil',
                                        'editor'
                                    ],
    'af_home_content_2' =>          [
                                        null,
                                        'Contenu n°2 de la page d\'accueil',
                                        'editor'
                                    ],
    'af_home_content_3' =>          [
                                        null,
                                        'Contenu n°3 de la page d\'accueil',
                                        'editor'
                                    ],
    'af_home_contact_title' =>      [
                                        "Contactez-moi :",
                                        'Titre de la section "Contact"'
                                    ],
    'sd_google_analytics_key' =>    [null,'Clé Google Analytics'],
    'sd_recaptcha_site_key' =>      [null,'Clé du site reCaptcha'],
    'sd_axeptio_key' =>            [null,'Clé Cookies Axeptio'],
    'sd_google_tag_key' =>            [null,'Clé Google Tag Manager'],
];

const MODELS = [
    'configs',
    'tokens'
];

<?php

$overrides = [];
$overridesFile = __DIR__ . '/overrides/overrides.nl.php';
if (file_exists($overridesFile)) {
    $overrides = require $overridesFile;
}

return $overrides + [
    'provided_by_attribute_source_url' => 'https://test.me',
    'accessibility' => [
        'button_expandable_screenreader' => ' ,knop, uitklapbaar',
        'button_screenreader' => ' ,knop, uitgeklapt',
        'consent_tooltip_screenreader' => 'Waarom hebben we jouw %attr_name% nodig?',

        'nav' => [
            'title' => 'Hoofdnavigatie',
        ],

        'skip_link' => 'Naar de hoofdinhoud',
    ],

    'general' => [
        'cancel' => 'Annuleren',
        'confirm' => 'Bevestigen',
        'home' => 'Naar de homepagina',
        'organisation_noun' => 'organisatie',
        'suite_name' => 'OpenConext',
    ],

    'profile' => [
        'application' => [
            'platform_connection_name' => 'SURFconext',
        ],

        'attribute_support' => [
            'explanation' => 'SURFconext support kan je vragen om bovenstaande informatie te delen. Deze informatie kan hen helpen om jouw supportvraag te beantwoorden.',
            'long_title' => 'Mail data naar SURFconext support',
            'send_mail' => 'Mail data',
            'short_title' => 'Mail data',
        ],

        'attribute_support_confirmation' => [
            'explanation' => 'De mail met informatie is succesvol verstuurd.',
            'long_title' => 'De attribuutdata zijn gemaild',
            'short_title' => 'Attribuutdata gemaild',
        ],

        'error' => [
            '403' => [
                'title' => 'Geen toegang (403)',
                'description' => 'Je hebt geen toegang tot deze pagina.',
            ],
            '404' => [
                'title' => 'Pagina niet gevonden (404)',
                'description' => 'De pagina kon niet gevonden worden.',
            ],
            '500' => [
                'title' => 'Onverwachte fout',
                'description' => 'Er ging iets mis. Probeer het opnieuw, of neem contact op met support als het probleem aanhoudt.',
            ],
            'back_home' => 'Terug naar de startpagina',
        ],

        'information_request' => [
            'explanation' => 'Door op de verzend-knop te drukken, worden uw attributen naar de Privacy Officer van SURF gestuurd en kan uw verzoek rondom de verwerking van persoonsgegevens in behandeling worden genomen.',
            'long_title' => 'Identificatie n.a.v. verzoek betrokkene',
            'send_mail' => 'Verzenden',
            'short_title' => 'Identificatieverzoek',
        ],

        'information_request_confirmation' => [
            'explanation' => 'Uw verzoek rondom de verwerking van persoonsgegevens zal in behandeling worden genomen.',
            'long_title' => 'Bedankt voor het verzenden van uw attributen',
            'short_title' => 'Identificatieverzoek',
        ],

        'introduction' => [
            'explanation' => [
                'introduction' => [
                    'end' => 'log je in met de account van je instelling bij verschillende (cloud)diensten.  Je kunt hier meer info vinden.',
                    'serviceName' => 'SURFconext',
                    'start' => 'Met',
                ],
            ],

            'long_title' => 'Hallo %userName%!',
            'long_title_user_name_replacement' => 'daar',

            'purpose' => [
                'profile_storage' => 'Op verzoek van jouw instelling geeft SURFconext een beperkt aantal persoonsgegevens door aan de dienst waar je inlogt. Soms gaat dit automatisch bij het inloggen, in andere gevallen moet jij vooraf expliciet toestemming geven voor de doorgifte van jouw gegevens.

            Deze profielpagina geeft je inzicht in welke persoonlijke data, afkomstig van jouw instelling, via SURFconext aan welke dienst wordt doorgegeven. Ook kun je zien welke gegevens door SURFconext worden opgeslagen en bij welke diensten je in het verleden bent ingelogd via SURFconext.',
                'title' => 'Wat kun je op deze profielpagina?
            ',
            ],

            'short_title' => 'Introductie',
        ],

        'locale' => [
            'choose_locale' => 'Kies taal',
            'choose_locale_label' => 'Kies uw taal',
            'en' => 'EN',
            'locale_change_fail' => 'Kon taal niet veranderen',
            'locale_change_success' => 'Taal veranderd',
            'nl' => 'NL',
            'pt' => 'PT',
        ],

        'my_connections' => [
            'active_connections' => 'Actieve koppelingen',
            'available_connections' => 'Beschikbare koppelingen',

            'delete_connection' => [
                'explanation' => 'Ben je zeker dat je jouw connectie met ORCID wil verwijderen en de toegang tot je gelinkte accounts wil herroepen?',
                'title' => 'Dienst verwijderen',
                'warning' => 'Een dienst zal je misschien niet meer herkennen de volgende keer je inlogt, waardoor je geen toegang meer hebt tot je gegevens.',
            ],

            'explanation' => 'Je kunt externe bronnen koppelen aan je SURFconext-profiel. SURFconext kan deze gegevens gebruiken om je bestaande attributen afkomstig van je instellingsaccount te verrijken met de waarden uit de gekoppelde account. Diensten die verbonden zijn met SURFconext kunnen vervolgens deze informatie ontvangen.',
            'long_title' => 'Accounts gelinkt aan je profiel',
            'missing_connections' => 'Missende koppelingen?',

            'no_active_connections' => [
                'description' => 'Je hebt nog geen actieve koppelingen.',
            ],

            'no_connections_configured' => [
                'description' => 'Op dit moment zijn er geen koppelingen beschikbaar die geconfigureerd kunnen worden.',
            ],

            'orcid' => [
                'connect_title' => 'Registreer of koppel je ORCID iD',

                'description' => [
                    'end' => 'Nadat je bent geredirected naar ORCID, kan je jouw ORCID iD linken door in te loggen en te klikken op "Autoriseren".  Vanaf dan kan je ORCID ID via SURFconext doorgegeven worden aan de diensten die het willen gebruiken.',
                    'start' => 'Verbind je bestaande of nieuw aangemaakte  ORCID iD eenmalig met SURFconext.  ORCID iD is een code die gebruikt wordt om wetenschappelijke- en academische auteurs uniek te identificeren.',
                ],

                'disconnect_title' => 'Ontkoppelen',
                'title' => 'ORCID',
            ],

            'send_request' => 'Stuur ons een verzoek',
            'short_title' => 'Mijn koppelingen',
        ],

        'my_profile' => [
            'attributes_information_link_title' => 'Attributen in SURFconext',

            'cards' => [
                'button' => 'Bekijken',

                'data' => [
                    'description' => 'Welke persoonlijke gegevens doorgegeven kunnen worden naar de diensten die je gebruikt.',
                    'title' => 'Jouw persoonlijke data',
                ],

                'services' => [
                    'description' => 'Bij welke diensten je in het verleden bent ingelogd via SURFconext.',
                    'title' => 'Gebruikte diensten',
                ],

                'store' => [
                    'description' => 'Welke gegevens SURFconext over jou opslaat.',
                    'title' => 'Wat we bewaren',
                ],
            ],

            'introduction' => 'De tabel hieronder biedt een overzicht van de persoonsgegevens die door jouw instelling via SURFconext kunnen worden doorgegeven aan diensten. In SURFconext worden jouw persoonsgegevens "attributen" genoemd. Een attribuut kan bijvoorbeeld je naam, e-mailadres of de naam van jouw instelling zijn. Voor technische informatie over deze attributen heeft SURFconext een aparte informatiepagina ingericht:',
            'questions' => 'Let op: jouw instelling is verantwoordelijk voor de persoonsgegevens die je hier ziet. SURFconext laat slechts de informatie zien zoals ontvangen van jouw instelling. Heb je vragen over je persoonsgegevens? Neem dan contact op met je instelling via:',
            'questions_no_support_contact_email' => 'Heb je vragen over je persoonsgegevens? Neem dan contact op met de helpdesk van je instelling.',
            'short_title' => 'Mijn profiel',
            'sup_text' => 'Je instelling beslist welke diensten toegankelijk voor je zijn via SURFconext.  De meeste diensten die je gebruikt via SURFconext vragen een subset van deze data.  Sommige diensten gebruiken geen persoonlijke data.  Indien je wil weten welke diensten welke data ontvingen, dan vind je dat bij',

            'user_data_download' => [
                'explanation' => 'Je kunt een overzicht van de persoonlijke data die SURFconext bewaard downloaden in json-formaat.',
                'link_text' => 'Download overzicht',
                'title' => 'Downloaden',
            ],
        ],

        'my_services' => [
            'consent_first_used_on' => 'Voor het eerst gebruikt op',
            'consent_type' => 'Toestemming gegeven door',
            'delete_button' => 'Verwijder login gegevens',
            'delete_explanation' => 'deze login gegevens verwijderen betekent dat de informatie verwijderd wordt van je SURFconext account.  Je hebt dan nog steeds een account bij de dienst zelf.  Indien je die ook wil verwijderen moet je dat bij de dienst zelf doen.',
            'entity_id' => 'Entity ID',
            'error_loading_consent' => 'De lijst met diensten waar je bent ingelogd kan niet opgehaald worden.',
            'eula' => 'Gebruikersovereenkomst',

            'explanation' => [
                'end' => 'Het toont welke subset van je persoonlijke data (attributen) gedeeld werd tussen je instelling en de dienst.  Daarnaast toont het ook of het jij of je instelling was die toestemming gaf om je attributen te delen met de dienst.',
                'start' => 'Dit overzicht bevat alle diensten waar je minstens één maal op ingelogd bent via SURFconext.',
            ],

            'explicit_consent_given' => 'gebruiker',

            'idpRow' => [
                'attributes_correction_text' => 'Foutieve informatie?',
                'consent_provided_by' => 'geleverd door',
                'correction_text' => '%suiteName% ontvangt de gegevens rechtstreeks van jouw %organisationNoun% en slaat deze zelf niet op. Neem contact op met de helpdesk van je %organisationNoun% als je gegevens niet kloppen.',
                'correction_title' => 'Kloppen de getoonde gegevens niet?',
                'support_link' => 'Uitleg',
                'support_url' => 'https://example.org',
            ],

            'implicit_consent_given' => 'instelling',
            'login_details' => 'Login details',
            'no_attribute_released' => 'Deze dienst ontvangt geen gegevens over jou.',

            'service_information' => [
                'title' => 'Informatie doorgegeven aan de dienst',
            ],

            'short_title' => 'Mijn diensten',
            'supportEmail' => 'E-mailadres support',
            'support_title' => 'Support',
            'support_url' => 'Support pagina\'s',
        ],

        'my_surf_conext' => [
            'account_data' => 'Accountgegevens',
            'account_data_explanation' => 'SURFconext kan (cloud)diensten een privacyvriendelijke identifier (nummer) geven waarmee je herkend kan worden als je opnieuw inlogt bij een dienst. Om dit te kunnen doen, moet SURFconext je Gebruikers-ID en de naam van jouw instelling opslaan.',
            'account_data_origin' => 'Gebruikers-ID en de naam van jouw instelling + nummer gegenereerd door SURFconext',
            'account_data_retention_period' => 'Tot 3 jaar na laatste inlog.',
            'consent_data' => 'Toestemmingsgegevens',
            'consent_data_explanation' => 'Bij de meeste diensten moet je, voordat je voor de eerste keer inlogt, expliciet toestemming geven om jouw attributen te delen met de dienst waar je wilt inloggen. SURFconext slaat op wanneer en voor welke dienst je deze toestemming hebt gegeven. Meer over welke gegevens je per dienst heb gedeeld, vind je onder',
            'consent_data_origin' => 'Gegenereerd door SURFconext',
            'consent_data_retention_period' => 'Tot 3 jaar na laatste inlog.',
            'data_origin' => 'Gegevens en herkomst',
            'data_retention_period' => 'Bewaartermijn',
            'introduction' => 'SURFconext slaat gegevens op om je eenvoudig en veilig in te kunnen laten loggen bij verschillende (cloud)diensten en om je inzicht te geven waar je allemaal bent ingelogd. Jouw instelling bepaalt welke diensten voor jou toegankelijk zijn via SURFconext. De meeste diensten die je via SURFconext benadert krijgen een klein deel van jouw gegevens. Sommige diensten hebben helemaal geen persoonsgegevens nodig. Als je wilt zien welke dienst welke gegevens krijgt, kijk dan bij %my_services_link%.',
            'logging_data' => 'Loggegevens',
            'logging_data_explanation' => 'SURFconext bewaart tijdelijk wanneer en vanaf welk IP-adres je gebruik maakt van SURFconext en bij welke diensten je hebt ingelogd. Dit is nodig voor het beheer en de beveiliging van SURFconext.',
            'logging_data_origin' => 'Gegenereerd door SURFconext',
            'logging_data_retention_period' => '6 maanden. Na 6 maanden worden de logbestanden geanonimiseerd.',
            'origin' => 'Herkomst',
            'short_title' => 'Mijn SURFconext',
        ],

        'navigation' => [
            'help' => 'Help',
            'privacy' => 'Privacy',
            'terms_of_service' => 'Gebruiksvoorwaarden',
        ],

        'saml' => [
            'attributes' => [
                'eduPersonTargetedId' => [
                    'persistent' => 'Pseudoniem dat per dienst verschilt',
                    'transient' => 'Pseudoniem dat per login verschilt',
                ],
            ],
        ],

        'table' => [
            'attribute_name' => 'Attribuut',
            'attribute_value' => 'Waarde',

            'source_description' => [
                'orcid' => 'ORCID iD',
                'sab' => 'SURF Autorisatie Beheer',
                'surfmarket_entitlements' => 'SURFmarket Entitlements',
                'voot' => 'Groepslidmaatschap',
            ],
        ],
    ],
];

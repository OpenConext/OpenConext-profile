<?php

$overrides = [];
$overridesFile = __DIR__ . '/../config/openconext/translationoverrides/overrides.nl.php';

//dd($overridesFile);
if (file_exists($overridesFile)) {
    $overrides = require $overridesFile;
}

return array_replace_recursive([
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
            'platform_connection_name' => '%suiteName%',
        ],

        'attribute_support' => [
            'explanation' => '%suiteName% support kan je vragen om bovenstaande informatie te delen. Deze informatie kan hen helpen om jouw supportvraag te beantwoorden.',
            'long_title' => 'Mail data naar %suiteName% support',
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

        'invite_roles' => [
            'long_title' => 'Je rollen',
            'short_title' => 'Je rollen',
            'intro' => 'Hier zijn de applicaties waar je via SURFconext Invite toegang toe hebt.',
            'no_results' => 'Er zijn geen rollen toegekend aan je account',
            'launch' => 'Open',
        ],
        'information_request_confirmation' => [
            'explanation' => 'Uw verzoek rondom de verwerking van persoonsgegevens zal in behandeling worden genomen.',
            'long_title' => 'Bedankt voor het verzenden van uw attributen',
            'short_title' => 'Identificatieverzoek',
        ],

        'introduction' => [
            'explanation' => [
                'introduction' => [
                    'end' => 'log je in met de account van je %organisationNoun% bij verschillende (cloud)diensten.  Je kunt hier meer info vinden.',
                    'serviceName' => '%suiteName%',
                    'start' => 'Met',
                ],
            ],

            'long_title' => 'Hallo %userName%!',
            'long_title_user_name_replacement' => 'daar',

            'purpose' => [
                'profile_storage' => 'Op verzoek van jouw %organisationNoun% geeft %suiteName% een beperkt aantal persoonsgegevens door aan de dienst waar je inlogt. Soms gaat dit automatisch bij het inloggen, in andere gevallen moet jij vooraf expliciet toestemming geven voor de doorgifte van jouw gegevens.

            Deze profielpagina geeft je inzicht in welke persoonlijke data, afkomstig van jouw %organisationNoun%, via %suiteName% aan welke dienst wordt doorgegeven. Ook kun je zien welke gegevens door %suiteName% worden opgeslagen en bij welke diensten je in het verleden bent ingelogd via %suiteName%.',
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
                'explanation' => 'Weet je zeker dat je jouw connectie met %serviceName% wil verwijderen en de toegang tot je gelinkte accounts wil herroepen?',
                'title' => 'Dienst verwijderen',
                'warning' => 'Een dienst zal je misschien niet meer herkennen de volgende keer je inlogt, waardoor je geen toegang meer hebt tot je gegevens.',
            ],

            'explanation' => 'Je kunt externe bronnen koppelen aan je %suiteName%-profiel. %suiteName% kan deze gegevens gebruiken om je bestaande attributen afkomstig van je %organisationNoun%saccount te verrijken met de waarden uit de gekoppelde account. Diensten die verbonden zijn met %suiteName% kunnen vervolgens deze informatie ontvangen.',
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
                    'end' => 'Nadat je bent geredirected naar ORCID, kan je jouw ORCID iD linken door in te loggen en te klikken op "Autoriseren". Vanaf dan kan je ORCID iD via %suiteName% doorgegeven worden aan de diensten die het willen gebruiken.',
                    'start' => 'Verbind je bestaande of nieuw aangemaakte ORCID iD eenmalig met %suiteName%. ORCID iD is een code die gebruikt wordt om wetenschappelijke- en academische auteurs uniek te identificeren.',
                ],

                'disconnect_title' => 'Ontkoppelen',
                'title' => 'ORCID',
            ],

            'send_request' => 'Stuur ons een verzoek',
            'short_title' => 'Je koppelingen',
        ],

        'my_profile' => [
            'attributes_information_link_title' => 'Attributen in %suiteName%',

            'cards' => [
                'button' => 'Bekijken',

                'data' => [
                    'description' => 'Welke persoonlijke gegevens doorgegeven kunnen worden naar de diensten die je gebruikt.',
                    'title' => 'Je persoonlijke data',
                ],

                'services' => [
                    'description' => 'Bij welke diensten je in het verleden bent ingelogd via %suiteName%.',
                    'title' => 'Gebruikte diensten',
                ],

                'store' => [
                    'description' => 'Welke gegevens %suiteName% over jou opslaat.',
                    'title' => 'Wat we bewaren',
                ],
            ],

            'introduction' => 'De tabel hieronder biedt een overzicht van de persoonsgegevens die door jouw %organisationNoun% via %suiteName% kunnen worden doorgegeven aan diensten. In %suiteName% worden jouw persoonsgegevens "attributen" genoemd. Een attribuut kan bijvoorbeeld je naam, e-mailadres of de naam van jouw %organisationNoun% zijn. Voor technische informatie over deze attributen heeft %suiteName% een aparte informatiepagina ingericht:',
            'questions' => 'Let op: jouw %organisationNoun% is verantwoordelijk voor de persoonsgegevens die je hier ziet. %suiteName% laat slechts de informatie zien zoals ontvangen van jouw %organisationNoun%. Heb je vragen over je persoonsgegevens? Neem dan contact op met je %organisationNoun% via:',
            'questions_no_support_contact_email' => 'Heb je vragen over je persoonsgegevens? Neem dan contact op met de helpdesk van je %organisationNoun%.',
            'short_title' => 'Je persoonlijke data',
            'sup_text' => 'Je %organisationNoun% beslist welke diensten toegankelijk voor je zijn via %suiteName%.  De meeste diensten die je gebruikt via %suiteName% vragen een subset van deze data.  Sommige diensten gebruiken geen persoonlijke data.  Indien je wil weten welke diensten welke data ontvingen, dan vind je dat bij',

            'user_data_download' => [
                'explanation' => 'Je kunt een overzicht van de persoonlijke data die %suiteName% bewaart downloaden in json-formaat.',
                'link_text' => 'Download overzicht',
                'title' => 'Downloaden',
            ],
        ],

        'my_services' => [
            'consent_first_used_on' => 'Voor het eerst gebruikt op',
            'consent_type' => 'Toestemming gegeven door',
            'delete_button' => 'Verwijder login gegevens',
            'delete_explanation' => 'Deze login gegevens verwijderen betekent dat de informatie verwijderd wordt van je %suiteName% account. Je hebt dan nog steeds een account bij de dienst zelf.  Indien je die ook wil verwijderen moet je dat bij de dienst zelf doen.',
            'entity_id' => 'Entity ID',
            'organization_name' => 'Aangeboden door',
            'error_loading_consent' => 'De lijst met diensten waar je bent ingelogd kan niet opgehaald worden.',
            'eula' => 'Gebruikersovereenkomst',

            'explanation' => [
                'end' => 'Het toont welke subset van je persoonlijke data (attributen) gedeeld werd tussen je %organisationNoun% en de dienst.  Daarnaast toont het ook of het jij of je %organisationNoun% was die toestemming gaf om je attributen te delen met de dienst.',
                'start' => 'Dit overzicht bevat alle diensten waar je minstens één maal op ingelogd bent via %suiteName%.',
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

            'implicit_consent_given' => '%organisationNoun%',
            'login_details' => 'Login details',
            'no_attribute_released' => 'Deze dienst ontvangt geen gegevens over jou.',

            'service_information' => [
                'title' => 'Informatie doorgegeven aan de dienst',
                'aa_text' => 'In aanvulling op de gegevens hierboven ontvangt deze dienst de volgende gegevens uit andere bronnen:',
            ],

            'short_title' => 'Gebruikte diensten',
            'supportEmail' => 'E-mailadres support',
            'support_title' => 'Support',
            'support_url' => 'Support pagina\'s',
        ],

        'my_surf_conext' => [
            'account_data' => 'Accountgegevens',
            'account_data_explanation' => '%suiteName% kan (cloud)diensten een privacyvriendelijke identifier (nummer) geven waarmee je herkend kan worden als je opnieuw inlogt bij een dienst. Om dit te kunnen doen, moet %suiteName% je Gebruikers-ID en de naam van jouw %organisationNoun% opslaan.',
            'account_data_origin' => 'Gebruikers-ID en de naam van jouw %organisationNoun% + nummer gegenereerd door %suiteName%',
            'account_data_retention_period' => 'Tot 3 jaar na laatste inlog.',
            'consent_data' => 'Toestemmingsgegevens',
            'consent_data_explanation' => 'Bij de meeste diensten moet je, voordat je voor de eerste keer inlogt, expliciet toestemming geven om jouw attributen te delen met de dienst waar je wilt inloggen. %suiteName% slaat op wanneer en voor welke dienst je deze toestemming hebt gegeven. Meer over welke gegevens je per dienst heb gedeeld, vind je onder',
            'consent_data_origin' => 'Gegenereerd door %suiteName%',
            'consent_data_retention_period' => 'Tot 3 jaar na laatste inlog.',
            'data_origin' => 'Gegevens en herkomst',
            'data_retention_period' => 'Bewaartermijn',
            'introduction' => '%suiteName% slaat gegevens op om je eenvoudig en veilig in te kunnen laten loggen bij verschillende (cloud)diensten en om je inzicht te geven waar je allemaal bent ingelogd. Jouw %organisationNoun% bepaalt welke diensten voor jou toegankelijk zijn via %suiteName%. De meeste diensten die je via %suiteName% benadert krijgen een klein deel van jouw gegevens. Sommige diensten hebben helemaal geen persoonsgegevens nodig. Als je wilt zien welke dienst welke gegevens krijgt, kijk dan bij %my_services_link%.',
            'logging_data' => 'Loggegevens',
            'logging_data_explanation' => '%suiteName% bewaart tijdelijk wanneer en vanaf welk IP-adres je gebruik maakt van %suiteName% en bij welke diensten je hebt ingelogd. Dit is nodig voor het beheer en de beveiliging van %suiteName%.',
            'logging_data_origin' => 'Gegenereerd door %suiteName%',
            'logging_data_retention_period' => '6 maanden. Na 6 maanden worden de logbestanden geanonimiseerd.',
            'origin' => 'Herkomst',
            'short_title' => 'Wat we bewaren',
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
                'voot' => 'Groepslidmaatschap',
                'invite' => '%suiteName% Invite',
                'manage' => 'SURF CRM',
                'eduid' => 'eduID',
                'ala' => 'eduID Account linking',
                'sbs' => 'SURF Research Access Management',
            ],
            'explanation' => [
                'singular' => 'Dit attribuut wordt',
                'plural' => 'Deze attributen worden',
                'text' => '%singularOrPlural% opgehaald op het moment dat je inlogt bij de dienst. We kunnen daarom de betreffende waarde hier niet laten zien.'
            ],
        ],
    ],
], $overrides);

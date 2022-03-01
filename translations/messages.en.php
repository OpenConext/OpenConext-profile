<?php

$overrides = [];
$overridesFile = __DIR__ . '/overrides/overrides.en.php';
if (file_exists($overridesFile)) {
    $overrides = require $overridesFile;
}

return $overrides + [
    'provided_by_attribute_source_url' => 'https://test.me',
    'accessibility' => [
        'button_expandable_screenreader' => ', button, expandable',
        'button_screenreader' => ', button, expanded',
        'consent_tooltip_screenreader' => 'Why do we need your %attr_name%?',

        'nav' => [
            'title' => 'Main navigation',
        ],

        'skip_link' => 'To main content',
    ],

    'general' => [
        'cancel' => 'Cancel',
        'confirm' => 'Confirm',
        'home' => 'Home',
        'organisation_noun' => 'organisation',
        'suite_name' => 'OpenConext',
    ],

    'profile' => [
        'application' => [
            'platform_connection_name' => 'SURFconext',
        ],

        'attribute_support' => [
            'explanation' => 'SURFconext support may ask you to share the abovementioned data. This information can help them to answer your support question.',
            'long_title' => 'Send data to SURFconext support',
            'send_mail' => 'Send data',
            'short_title' => 'Send data',
        ],

        'attribute_support_confirmation' => [
            'explanation' => 'The mail with your information has been successfully sent.',
            'long_title' => 'Attribute data has been mailed',
            'short_title' => 'Attribute data mailed',
        ],

        'information_request' => [
            'explanation' => 'If you press \'confirm request\', your attributes will be sent to SURF\'s Privacy Officer, and your request about the processing of personal data will be taken care of.',
            'long_title' => 'Identification because of request data subject',
            'send_mail' => 'Confirm request',
            'short_title' => 'Identification request',
        ],

        'information_request_confirmation' => [
            'explanation' => 'Your request about the processing of personal data will be taken care of.',
            'long_title' => 'Thanks for sending your attributes',
            'short_title' => 'Identification request',
        ],

        'introduction' => [
            'explanation' => [
                'introduction' => [
                    'end' => 'you login with your institutional account on different (cloud)services. You can find more info here.',
                    'serviceName' => 'SURFconext',
                    'start' => 'With',
                ],
            ],

            'long_title' => 'Hi %userName%!',
            'long_title_user_name_replacement' => 'there',

            'purpose' => [
                'profile_storage' => 'On behalf of your institution, SURFconext forwards a limited amount of your personal data to the service you are logging in to. Most of the times you are required to give explicit consent for this information transfer. In some cases, however, this feature is disabled on request of your institution.

            This profile page gives you insight in which personal data, provided by your institution via SURFconext, has been forwarded to which service. You can also review which personal data is being stored by SURFconext and which services you have logged in to in the past.',
                'title' => 'How SURFconext works',
            ],

            'short_title' => 'Home',
        ],

        'locale' => [
            'choose_locale' => 'Choose language',
            'choose_locale_label' => 'Choose your language',
            'en' => 'EN',
            'locale_change_fail' => 'Could not change language',
            'locale_change_success' => 'Language changed',
            'nl' => 'NL',
            'pt' => 'PT',
        ],

        'my_connections' => [
            'active_connections' => 'Active connections',
            'available_connections' => 'Available Connections',

            'delete_connection' => [
                'explanation' => 'Are you sure you want to delete your connection to ORCID and revoke access to your linked accounts?',
                'title' => 'Delete Service',
                'warning' => 'A service might not recognize you the next time you login and all personal data within this service might no longer be available.',
            ],

            'explanation' => 'It\'s possible to connect external sources to your SURFconext profile. SURFconext can use this information to enrich the existing attributes of your institutional account with the values from this external account. Services connected to SURFconext can receive and use this information.',
            'long_title' => 'Accounts linked to your profile',
            'missing_connections' => 'Missing Connections?',

            'no_active_connections' => [
                'description' => 'You don\'t have any active connections yet.',
            ],

            'no_connections_configured' => [
                'description' => 'At the moment there are no connections that are available for configuration.',
            ],

            'orcid' => [
                'connect_title' => 'Register or Connect your ORCID iD',

                'description' => [
                    'end' => 'After you have been redirected to ORCID, you can link your ORCID iD by logging in and clicking on \'Authorize\'. In the future, your ORCID iD can then be passed on via SURFconext to services that want to use it.',
                    'start' => 'Connect your existing or newly created ORCID iD to SURFconext once. The ORCID iD is a code that is used to uniquely identify scientific and other academic authors.',
                ],

                'disconnect_title' => 'Disconnect',
                'title' => 'ORCID',
            ],

            'send_request' => 'Send us a request',
            'short_title' => 'My Connections',
        ],

        'my_profile' => [
            'attributes_information_link_title' => 'Attributes in SURFconext',

            'cards' => [
                'button' => 'View',

                'data' => [
                    'description' => 'Which personal data can be passed on to the services your using.',
                    'title' => 'Your personal data',
                ],

                'services' => [
                    'description' => 'Which services you logged in to in the past using SURFconext.',
                    'title' => 'Accessed services',
                ],

                'store' => [
                    'description' => 'Which data SURFconext stores from you.',
                    'title' => 'What we store',
                ],
            ],

            'introduction' => 'The table below contains an overview of all your personal data that your institution can pass on to several services through SURFconext. Within SURFconext, your personal data are called \'attributes\'. An attribute can for instance be your name, your e-mail address or the name of your institution. For more technical information about these attributes, SURFconext provides the following extra information page:',
            'questions' => 'Please note: your institution is responsible for the personal data displayed here. SURFconext is simply showing the information received from your institution. If you have any questions about your attributes, please contact the help desk of your institution through:',
            'questions_no_support_contact_email' => 'If you have any questions about your personal data, please contact the help desk of your institution.',
            'short_title' => 'Your personal data',
            'sup_text' => 'Your institution decides which services are accessible for you through SURFconext. Most services you use through SURFconext request a subset of this data. Some services require no personal data. If you want to see which services received which data, please refer to',

            'user_data_download' => [
                'explanation' => 'You can download an overview with personal data stored by SURFconext in json format.',
                'link_text' => 'Download overview',
                'title' => 'Download',
            ],
        ],

        'my_services' => [
            'consent_first_used_on' => 'First used on',
            'consent_type' => 'Consent was given by',
            'delete_button' => 'Delete login details',
            'delete_explanation' => 'deleting these login details means SURFconext removes this information from your SURFconext account.  You still have an account at the service itself.  If you want that removed, please do so at the service.',
            'entity_id' => 'Entity ID',
            'error_loading_consent' => 'The list of services which you are logged in to cannot be retrieved.',
            'eula' => 'EULA',

            'explanation' => [
                'end' => 'It shows which subset of your personal data (attributes) has been shared between your institution and the service. Additionally it shows whether you or your institutiton has agreed to sharing your attributes with the service.',
                'start' => 'This overview contains all services you have logged in to through SURFconext at least once.',
            ],

            'explicit_consent_given' => 'user',

            'idpRow' => [
                'attributes_correction_text' => 'Something incorrect?',
                'consent_provided_by' => 'provided by',
                'correction_text' => '%suiteName% receives the information directly from your %organisationNoun% and does not store the information itself. If your information is incorrect, please contact the service desk of your %organisationNoun% to change it.',
                'correction_title' => 'Is the data shown incorrect?',
                'support_link' => 'Explanation',
                'support_url' => 'https://example.org',
            ],

            'implicit_consent_given' => 'institution',
            'login_details' => 'Login details',
            'no_attribute_released' => 'This service does not receive information about you.',

            'service_information' => [
                'title' => 'Information transferred to the service',
            ],

            'short_title' => 'Services you accessed',
            'supportEmail' => 'Contact support',
            'support_title' => 'Support',
            'support_url' => 'Support pages',
        ],

        'my_surf_conext' => [
            'account_data' => 'Account data',
            'account_data_explanation' => 'SURFconext can provide (cloud) services a privacy-friendly identifier (number) with which you can be recognized when you return to the service.',
            'account_data_origin' => 'User ID and institution name from your institution + number generated by SURFconext',
            'account_data_retention_period' => 'Until 3 years after last login.',
            'consent_data' => 'Consent Data',
            'consent_data_explanation' => 'Most services require you, prior to the first login, to explicitly give consent to your institution to share your personal data with the service you are logging in to. SURFconext stores at which moment and for which service you have given consent. To see which personal data is being shared with which service, please go to',
            'consent_data_origin' => 'Generated by SURFconext',
            'consent_data_retention_period' => 'Until 3 years after last login.',
            'data_origin' => 'Information and origin',
            'data_retention_period' => 'Retention Period',
            'introduction' => 'SURFconext stores the following data to allow you to log in easily and securely to various (cloud) services.',
            'logging_data' => 'Logging Data',
            'logging_data_explanation' => 'SURFconext logs when you use SURFconext, which service you are logging in to and from which IP address. This is necessary for administration and security purposes.',
            'logging_data_origin' => 'Generated by SURFconext',
            'logging_data_retention_period' => '6 months. After 6 months the log files are anonymized.',
            'origin' => 'Origin',
            'short_title' => 'What we store',
        ],

        'navigation' => [
            'help' => 'Help & FAQ',
            'privacy' => 'Privacy policy',
            'terms_of_service' => 'Terms of Use',
        ],

        'saml' => [
            'attributes' => [
                'eduPersonTargetedId' => [
                    'persistent' => 'Pseudonym that differs for each service',
                    'transient' => 'Pseudonym that changes with every login',
                ],
            ],
        ],

        'table' => [
            'attribute_name' => 'Attribute',
            'attribute_value' => 'Your values',

            'source_description' => [
                'orcid' => 'ORCID iD',
                'sab' => 'SURF Autorisatie Beheer',
                'surfmarket_entitlements' => 'SURFmarket Entitlements',
                'voot' => 'Group membership',
            ],
        ],
    ],
];
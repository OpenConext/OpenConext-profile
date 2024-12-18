<?php

$overrides = [];
$overridesFile = __DIR__ . '/../config/openconext/translationoverrides/overrides.en.php';
if (file_exists($overridesFile)) {
    $overrides = require $overridesFile;
}

return array_replace_recursive([
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
    'suite_name' => 'OpenConext',
    'general' => [
        'cancel' => 'Cancel',
        'confirm' => 'Confirm',
        'home' => 'Home',
        'organisation_noun' => 'organisation',
        'suite_name' => 'OpenConext',
    ],
    'profile' => [
        'application' => [
            'platform_connection_name' => '%suiteName%',
        ],

        'attribute_support' => [
            'explanation' => '%suiteName% support may ask you to share the abovementioned data. This information can help them to answer your support question.',
            'long_title' => 'Send data to %suiteName% support',
            'send_mail' => 'Send data',
            'short_title' => 'Send data',
        ],

        'attribute_support_confirmation' => [
            'explanation' => 'The mail with your information has been successfully sent.',
            'long_title' => 'Attribute data has been mailed',
            'short_title' => 'Attribute data mailed',
        ],

        'error' => [
            '403' => [
                'title' => 'No access (403)',
                'description' => 'You do not have access to this page.',
            ],
            '404' => [
                'title' => 'Page not found (404)',
                'description' => 'The page could not be found.',
            ],
            '500' => [
                'title' => 'Unexpected error',
                'description' => 'Something went wrong. Please try again, or contact support if the problem persists.',
            ],
            'back_home' => 'Back home',
        ],

        'information_request' => [
            'explanation' => 'If you press \'confirm request\', your personal data will be sent to SURF\'s Privacy Officer, and your request about the processing of personal data will be taken care of.',
            'long_title' => 'Identification because of request data subject',
            'send_mail' => 'Confirm request',
            'short_title' => 'Identification request',
        ],

        'invite_roles' => [
            'long_title' => 'Your roles',
            'short_title' => 'Your roles',
            'intro' => 'Here are the applications you can access through SURFconext Invite.',
            'no_results' => 'There are no roles assigned to your account',
            'launch' => 'Launch',
        ],

        'information_request_confirmation' => [
            'explanation' => 'Your request about the processing of personal data will be taken care of.',
            'long_title' => 'Thanks for sending your personal data',
            'short_title' => 'Identification request',
        ],

        'introduction' => [
            'explanation' => [
                'introduction' => [
                    'end' => 'you log in with your %organisationNoun%al account on different (cloud)applications. You can find more info here.',
                    'serviceName' => '%suiteName%',
                    'start' => 'With',
                ],
            ],

            'long_title' => 'Hi %userName%!',
            'long_title_user_name_replacement' => 'there',

            'purpose' => [
                'profile_storage' => 'On behalf of your %organisationNoun%, %suiteName% forwards a limited amount of your personal data to the application you are logging in to. Most of the times you are required to give explicit consent for this information transfer. In some cases, however, this feature is disabled on request of your %organisationNoun%.

            This profile page gives you insight in which personal data, provided by your %organisationNoun% via %suiteName%, has been forwarded to which application. You can also review which personal data is being stored by %suiteName% and which applications you have logged in to in the past.',
                'title' => 'How %suiteName% works',
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
                'explanation' => 'Are you sure you want to delete your connection to %serviceName% and revoke access to your linked accounts?',
                'title' => 'Delete application',
                'warning' => 'An application might not recognize you the next time you log in and all personal data within this application might no longer be available.',
            ],

            'explanation' => 'It\'s possible to connect external sources to your %suiteName% profile. %suiteName% can use this information to enrich the existing personal data of your %organisationNoun%al account with the values from this external account. Applications connected to %suiteName% can receive and use this information.',
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
                    'end' => 'After you have been redirected to ORCID, you can link your ORCID iD by logging in and clicking on \'Authorize\'. In the future, your ORCID iD can then be passed on via %suiteName% to applications that want to use it.',
                    'start' => 'Connect your existing or newly created ORCID iD to %suiteName% once. The ORCID iD is a code that is used to uniquely identify scientific and other academic authors.',
                ],

                'disconnect_title' => 'Disconnect',
                'title' => 'ORCID',
            ],

            'send_request' => 'Send us a request',
            'short_title' => 'Your connections',
        ],

        'my_profile' => [
            'attributes_information_link_title' => 'Personal data in %suiteName%',

            'cards' => [
                'button' => 'View',

                'data' => [
                    'description' => 'Which personal data could be transferred to the applications you use through %suiteName%.',
                    'title' => 'Your personal data',
                ],

                'services' => [
                    'description' => 'Which applications you logged in to in the past using %suiteName%.',
                    'title' => 'Applications',
                ],

                'store' => [
                    'description' => 'Which applications you previously logged in to using %suiteName%.',
                    'title' => 'What we store',
                ],
            ],

            'introduction' => 'The table below contains an overview of all your personal data that your %organisationNoun% can pass on to several applications through %suiteName%, for instance your name, your e-mail address or the name of your %organisationNoun%. For more technical information about this personal data, %suiteName% provides the following extra information page:',
            'questions' => 'Please note: your %organisationNoun% is responsible for the personal data displayed here. %suiteName% is simply showing the information received from your %organisationNoun%. If you have any questions about your personal data, please contact the help desk of your %organisationNoun% through:',
            'questions_no_support_contact_email' => 'If you have any questions about your personal data, please contact the help desk of your %organisationNoun%.',
            'short_title' => 'Your personal data',
            'sup_text' => 'Your %organisationNoun% decides which applications are accessible for you through %suiteName%. Most applications you use through %suiteName% request a subset of this data. Some applications require no personal data. If you want to see which applications received which data, please refer to',

            'user_data_download' => [
                'explanation' => 'You can download an overview with personal data stored by %suiteName% in json format.',
                'link_text' => 'Download overview',
                'title' => 'Download',
            ],
        ],

        'my_services' => [
            'consent_first_used_on' => 'First used on',
            'consent_type' => 'Consent was given by',
            'delete_button' => 'Delete login details',
            'delete_explanation' => 'Deleting these login details means %suiteName% removes this information from your %suiteName% account.  You still have an account at the application itself.  If you want that removed, please do so at the application.',
            'entity_id' => 'Entity ID',
            'organization_name' => 'Offered by',
            'error_loading_consent' => 'The list of applications which you are logged in to cannot be retrieved.',
            'eula' => 'EULA',

            'explanation' => [
                'end' => 'It shows which subset of your personal data has been shared between your %organisationNoun% and the application. Additionally it shows whether you or your %organisationNoun% has agreed to sharing your personal data with the application.',
                'start' => 'This overview contains all applications you have logged in to through %suiteName% at least once.',
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

            'implicit_consent_given' => '%organisationNoun%',
            'login_details' => 'Login details',
            'no_attribute_released' => 'This application does not receive information about you.',

            'service_information' => [
                'title' => 'Information transferred to the application',
                'aa_text' => 'In addition to the personal data listed above, this application also receives personal data from the following sources:',
            ],

            'short_title' => 'Applications',
            'supportEmail' => 'Contact support',
            'support_title' => 'Support',
            'support_url' => 'Support pages',
        ],

        'my_surf_conext' => [
            'account_data' => 'Account data',
            'account_data_explanation' => '%suiteName% can provide (cloud) applications a privacy-friendly identifier (number) with which you can be recognized when you return to the application.',
            'account_data_origin' => 'User ID and %organisationNoun% name from your %organisationNoun% + number generated by %suiteName%',
            'account_data_retention_period' => 'Until 3 years after last login.',
            'consent_data' => 'Consent Data',
            'consent_data_explanation' => 'Most applications require you, prior to the first login, to explicitly give consent to your %organisationNoun% to share your personal data with the application you are logging in to. %suiteName% stores at which moment and for which application you have given consent. To see which personal data is being shared with which application, please go to',
            'consent_data_origin' => 'Generated by %suiteName%',
            'consent_data_retention_period' => 'Until 3 years after last login.',
            'data_origin' => 'Information and origin',
            'data_retention_period' => 'Retention Period',
            'introduction' => '%suiteName% stores the following data to allow you to log in easily and securely to various (cloud) applications.',
            'logging_data' => 'Logging Data',
            'logging_data_explanation' => '%suiteName% logs when you use %suiteName%, which application you are logging in to and from which IP address. This is necessary for administration and security purposes.',
            'logging_data_origin' => 'Generated by %suiteName%',
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
                    'persistent' => 'Pseudonym that differs for each application',
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
                'voot' => 'Group membership',
                'invite' => '%suiteName% Invite',
                'manage' => 'SURF CRM',
                'eduid' => 'eduID',
                'ala' => 'eduID Account linking',
                'sbs' => 'SURF Research Access Management',
            ],
            'explanation' => [
                'singular' => 'This personal data is',
                'plural' => 'These personal data are',
                'text' => '%singularOrPlural% retrieved at the time you log in to the application. We are therefore unable to show the actual values here.'
            ]
        ],
    ],
], $overrides);

<?php

$overrides = [];
$overridesFile = __DIR__ . '/overrides/overrides.pt.php';
if (file_exists($overridesFile)) {
    $overrides = require $overridesFile;
}

return $overrides + [
    'provided_by_attribute_source_idp' => 'Ibuildings University',
    'provided_by_attribute_source_url' => 'https://test.me',
    'provided_by_attribute_source_logo_url_idp' => 'build/images/surf.svg',
    'accessibility' => [
        'button_expandable_screenreader' => ', botao, expansível',
        'button_screenreader' => ', botao, expandido',
        'consent_tooltip_screenreader' => 'Porque precisamos do seu %attr_name%?',

        'nav' => [
            'title' => 'Navegação principal',
        ],

        'skip_link' => 'Para o conteúdo principal',
    ],

    'general' => [
        'cancel' => 'Cancel',
        'confirm' => 'Confirm',
        'home' => 'Home',
        'organisation_noun' => 'organização',
        'suite_name' => 'OpenConext',
    ],

    'profile' => [
        'application' => [
            'platform_connection_name' => 'SURFconext',
        ],

        'attribute_support' => [
            'explanation' => 'O suporte da SURFconext poderá pedir-lhe para partilhar os dados mencionados em cima. Estes dados podem ajudar o suporte a resolver as suas questões.',
            'long_title' => 'Enviar os dados ao suporte da SURFconext',
            'send_mail' => 'Enviar dados',
            'short_title' => 'Enviar dados',
        ],

        'attribute_support_confirmation' => [
            'explanation' => 'O email com os seus dados foi enviado com sucesso.',
            'long_title' => 'Os atributos foram enviados por email',
            'short_title' => 'Atributo enviado por email',
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
                    'end' => 'você faz o login com sua conta do instituto em diferentes serviços (cloud). Pode encontrar mais informação aqui.',
                    'serviceName' => 'SURFconext',
                    'start' => 'Com',
                ],
            ],

            'long_title' => 'Página do perfil da SURFconext',
            'long_title_user_name_replacement' => 'profile.introduction.long_title_user_name_replacement',

            'purpose' => [
                'profile_storage' => 'Em nome da sua instituição, a SURFconext envia uma quantidade limitada de seus dados pessoais para o serviço no qual você está a realizar a sua autenticação. Na maioria das vezes, você é obrigado a dar consentimento explícito para a transferência de informações. No entanto, em alguns casos, esse recurso é desativado a pedido de sua instituição.

            Esta página de perfil fornece informações que podem vir a ser dados pessoais. Estes dados são fornecidos pela sua instituição por meio da SURFconext que são encaminhados para cada serviço. Você também pode validar que dados pessoais estão a ser armazenados pela SURFconext e quais os serviços utilizou no passado.',
                'title' => 'Qual é o propósito desta página de perfil?',
            ],

            'short_title' => 'Introdução',
        ],

        'locale' => [
            'choose_locale' => 'Escolher idioma',
            'choose_locale_label' => 'Choose your language',
            'en' => 'EN',
            'locale_change_fail' => 'Idioma não pode ser alterado',
            'locale_change_success' => 'Idioma alterado',
            'nl' => 'NL',
            'pt' => 'PT',
        ],

        'my_connections' => [
            'active_connections' => 'Ligações activas',
            'available_connections' => 'Ligações disponíveis',

            'delete_connection' => [
                'explanation' => 'Are you sure you want to delete your unique pseudonymised eduId for ORCID and revoke access to your linked accounts?',
                'title' => 'Delete Service',
                'warning' => 'This service might not recognize you the next time you login and all your personal data within this service might be lost.',
            ],

            'explanation' => 'É possível ligar fontes externas ao seu Perfil SURFconext. A SURFconext consegue usar esta informação para enriquecer os atributos existentes da sua conta institucional com os valores desta conta externa. Os serviços ligados à SURFconext podem receber e usar esta informação.',
            'long_title' => 'Contas ligadas ao seu perfil',
            'missing_connections' => 'Ligações em falta?',

            'no_active_connections' => [
                'description' => 'Não tem ligações activas.',
            ],

            'no_connections_configured' => [
                'description' => 'De momento não existem ligações disponíveis para configuração.',
            ],

            'orcid' => [
                'connect_title' => 'Registre-se ou conecte seu ORCID iD',

                'description' => [
                    'end' => 'Depois de ser redirecionado para o ORCID, você pode vincular o seu ID do ORCID fazendo login e clicando em \' Autorizar \'. No futuro, o seu ID de ORCID pode ser passado via SURFconext para os serviços que desejam usá-lo.',
                    'start' => 'Ligue o seu ORCID iD existente ou recém-criado na SURFconext uma vez. O ORCID iD é um código que é usado para identificar exclusivamente autores científicos e outros acadêmicos.',
                ],

                'disconnect_title' => 'Desligar',
                'title' => 'ORCID',
            ],

            'send_request' => 'Envie-nos um pedido',
            'short_title' => 'As Minhas Ligações',
        ],

        'my_profile' => [
            'attributes_information_link_title' => 'Atributos na SURFconext',

            'cards' => [
                'button' => 'Ver',

                'data' => [
                    'description' => 'Que dados pessoais podem ser fornecidos aos serviços que você usa.',
                    'title' => 'Your personal data',
                ],

                'services' => [
                    'description' => 'Em que serviços se ligou anteriormente usando SURFconext.',
                    'title' => 'Serviços acedidos',
                ],

                'store' => [
                    'description' => 'Que dados seus são armazenados pela SURFconext.',
                    'title' => 'O que armazenamos',
                ],
            ],

            'introduction' => 'A tabela apresentada em baixo contém uma visão geral de todos os seus dados pessoais que a sua instituição de origem poderá passar para os vários serviços, através da SURFconext. Dentro da SURFconext, os seus dados pessoais são chamados de \'atributos\'. Um atributo pode ser, por exemplo, o seu nome, o seu endereço de e-mail ou o nome da sua instituição. Para obter mais informações técnicas sobre esses atributos, a SURFconext disponibiliza a seguinte página de informações extras:',
            'questions' => 'Nota importante: A sua instituição é responsável pelos dados pessoais aqui exibidos. A SURFconext está simplesmente a mostrar as informações facultadas pela sua instituição de origem. Se tiver alguma questão sobre os seus atributos, por favor contate o suporte da sua instituição através de:',
            'questions_no_support_contact_email' => 'Se tiver alguma dúvida sobre seus dados pessoais, entre em contato com o suporte técnico da sua instituição.',
            'short_title' => 'O Meu Perfil',
            'sup_text' => 'Sua instituição decide que serviços tem acesso na SURFconext. A maioria dos serviços que você usa por meio do SURFconext solicita um subconjunto desses dados. Alguns serviços não requerem dados pessoais. Se pretender ver que serviços receberam e que dados, consulte em',

            'user_data_download' => [
                'explanation' => 'Pode efetuar o download da visão geral com dados pessoais armazenados pela SURFconext em formato json.',
                'link_text' => 'Download visão geral',
                'title' => 'Download',
            ],
        ],

        'my_services' => [
            'consent_first_used_on' => 'Utilizado pela primeira vez em',
            'consent_type' => 'O consentimento foi dado por',
            'delete_button' => 'Apagar detalhes do login',
            'delete_explanation' => 'excluir esses detalhes de login significa que a SURFconext remove essas informações da sua conta SURFconext. Você ainda continua a ter conta no serviço em si. Mas se pretender que seja removido, faça-o no serviço.',
            'entity_id' => 'Entity ID',
            'error_loading_consent' => 'A lista dos serviços nos quais está autenticado não pode ser apresentada.',
            'eula' => 'EULA',

            'explanation' => [
                'end' => 'Mostra qual o subconjunto dos dados pessoais (atributos) foi partilhado entre sua instituição e o serviço. Além disso, mostra se você ou a sua instituição concordou em compartilhar os seus atributos com o serviço.',
                'start' => 'Esta visão geral contém todos os serviços nos quais você se ligou através da SURFconext pelo menos uma vez.',
            ],

            'explicit_consent_given' => 'utilizador',

            'idpRow' => [
                'attributes_correction_text' => 'Something incorrect?',
                'consent_provided_by' => 'provided by',
                'correction_text' => '%suiteName% receives the information directly from your %organisationNoun% and does not store the information itself. If your information is incorrect, please contact the service desk of your %organisationNoun% to change it.',
                'correction_title' => 'Is the data shown incorrect?',
                'support_link' => 'Explanation',
                'support_url' => 'https://example.org',
            ],

            'implicit_consent_given' => 'instituição',
            'login_details' => 'Detalhes do Login',
            'no_attribute_released' => 'Este serviço não recebe informações sobre si.',

            'service_information' => [
                'title' => 'Information transferred to the service',
            ],

            'short_title' => 'Os Meus Serviços',
            'supportEmail' => 'Email de Suporte',
            'support_title' => 'Suporte',
            'support_url' => 'Suporte',
        ],

        'my_surf_conext' => [
            'account_data' => 'Dados da conta',
            'account_data_explanation' => 'A SURFconext pode fornecer a cada serviço (cloud) um identificador de privacidade (código], através do qual o utilizador pode ser reconhecido quando voltar a usar o serviço.',
            'account_data_origin' => 'O ID de utilizador e nome da sua instituição através da sua instituição + número gerado pela SURFconext',
            'account_data_retention_period' => 'Até 3 anos após o último login.',
            'consent_data' => 'Dados de Consentimento',
            'consent_data_explanation' => 'A maioria dos serviços exigem que o utilizador, antes do primeiro login, dê o seu consentimento explícito à sua instituição para partilha de dados pessoais com o serviço no qual pretende efetuar login. A SURFconext armazena em que momento e para que serviço foi dado consentimento. Para ver que dados pessoais estão a ser partilhados com o serviço, aceda a',
            'consent_data_origin' => 'Gerado pela SURFconext',
            'consent_data_retention_period' => 'Até 3 anos após o último login.',
            'data_origin' => 'Informação e origem',
            'data_retention_period' => 'Período de Retenção',
            'introduction' => 'A SURFconext armazena um conjunto de dados para permitir login fácil e seguro em diversos serviços (na Cloud) e também para fornecer informações sobre os serviços nos quais efetuou login através da SURFconext. A instituição de origem é quem decide que serviços estão acessíveis para o utilizador através da SURFconext. A maioria dos serviços usados através da SURFconext solicita um subconjunto desses dados. Alguns serviços não requerem dados pessoais; caso o utilizador queira ver que dados foram partilhados e por que serviços, deverá consultar o separador %my_services_link%.',
            'logging_data' => 'Registo de Dados',
            'logging_data_explanation' => 'A SURFconext faz um registo de acesso de cada utilizador, do serviço em que este efetua login e do endereço IP através do qual acede. Este registo é apenas para fins administrativos e de segurança.',
            'logging_data_origin' => 'Gerado pela SURFconext',
            'logging_data_retention_period' => '6 meses. Ao fim de 6 meses os registos são tornados anónimos.',
            'origin' => 'Origem',
            'short_title' => 'O Meu SURFconext',
        ],

        'navigation' => [
            'help' => 'Ajuda',
            'privacy' => 'profile.navigation.privacy',
            'terms_of_service' => 'Termos do Serviço',
        ],

        'saml' => [
            'attributes' => [
                'eduPersonTargetedId' => [
                    'persistent' => 'Pseudônimo que difere para cada serviço',
                    'transient' => 'Pseudônimo que muda a cada login',
                ],
            ],
        ],

        'table' => [
            'attribute_name' => 'Atributo',
            'attribute_value' => 'Valor',

            'source_description' => [
                'orcid' => 'ORCID iD',
                'sab' => 'Gestão de Autorização SURFconext',
                'surfmarket_entitlements' => 'Direitos SURFconext',
                'voot' => 'Membros do Grupo',
            ],
        ],
    ],
];

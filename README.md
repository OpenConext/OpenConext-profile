OpenConext-profile
==================

[![Build Status](https://travis-ci.org/OpenConext/OpenConext-profile.svg)](https://travis-ci.org/OpenConext/OpenConext-profile)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/OpenConext/OpenConext-profile/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/OpenConext/OpenConext-profile/?branch=develop)

OpenConext Profile can present a logged in user with an overview of all the
attributes their IdP provides, a list of services they have logged in to
and any other information that is known about the user.

It can be used for informational purposes, to provide users insight into what
data is kept and exchanged by OpenConext, and for debugging. Also the user
can opt to ask to remove the information that OpenConext has stored about
them.

Profile is basically a SAML SP which displays the attributes it receives
from the IdP (OpenConext Engineblock) and requests and displays additional
information via EngineBlock's internal API.

## Requirements
- PHP 7.0
- EngineBlock 5.6 >= 5.6.7 
- EngineBlock 5.7 >= 5.7.1
- EngineBlock must be configured to release an unspecified NameID to Profile

## Development

You can use docker to start a development environment.

### Docker

The docker container comes with EB and profile already installed & configured.  Follow the below steps to get it up and running.

1. Clone the repo
2. Copy .env file: `cp ./.env.dist ./.env` and fill it with sensible settings (for instance use the dev env)
2. Copy parameters file: `cp config/legacy/parameters.yaml.dist config/legacy/parameters/parameters.yaml`
3. Copy global_view_parameters file: `cp config/legacy/parameters/global_view_parameters.yaml.dist config/legacy/parameters/global_view_parameters.yaml`
4. Install composer dependencies: `SYMFONY_ENV=dev composer install --prefer-dist`
5. Install npm dependencies: `npm i`
6. Run a build: `npm run build`
7. Ensure the var folder has the correct rights: `chmod -R 777 var/`
9. Start developing on docker: `docker-compose up -d`

## Attribute aggregation support
Supported attribute aggregation attributes can be configured in the config.yml file. The example below uses
the orcid as a configuration example.

```yaml
open_conext_profile:
    # Other config values are ommitted
    
    attribute_aggregation_supported_attributes:
        # The identifier of the attribute, should match the Attribute Aggregation API's definition
        ORCID:
            # The relative path to an image. Starting from the /web folder
            logo_path: %attribute_aggregation_orcid_logo_path%
            # The Url where the attribute can be connected
            connect_url: %attribute_aggregation_orcid_connect_url%
```

## User Lifecycle support
By enabling the User Lifecyle API integration, you enable users of the platform to download an overview of their 
personal data stored by SURFconext. To enable the API add the following configuration to the `parameters.yaml`

```yaml
    user_lifecycle_enabled: true
    user_lifecycle_api_base_url: 'https://user-lifecycle.example.com/' # the application knows the location of the endpoint
    user_lifecycle_api_username: userlifecycle
    user_lifecycle_api_password: secret
    user_lifecycle_api_verify_ssl: false
``` 

Make sure to fill all parameters, also when the `user_lifecycle_enabled` toggle is disabled.

See the User Lifecycle project on [GitHub](https://github.com/OpenConext/OpenConext-user-lifecycle) for more information.

## EngineBlock consent removal support
In order to allow user to retract consent for a given service. You can enable the remove consent feature in the 
`parameters.yaml`. By setting `remove_consent_enabled` to `true`, every service in the 'my services' page will have a 
delete button. Clicking this button will retract consent for only that service.

In order for this feature to work, you need to have an EngineBlock instance that supports this feature. See the
EngineBlock docs for more information on enabling the feature on the EngineBlock Api.

## Release strategy
Please read: https://github.com/OpenConext/Stepup-Deploy/wiki/Release-Management for more information on the release strategy used in Openconext projects.

## Deployment
Run `./bin/makeRelease.sh` with the version number of the relevant release to create a deployable tar-ball.

During deployment, unpack the tar on the deployment target and configure the
application by placing the required `parameters.yaml` and
`global_view_parameters.yaml` files in the `config/legacy` directory.
To prepare the application environment, run `composer prepare-env` on the
deployment target.

Make sure to set the correct Symfony environment by setting or exporting
`SYMFONY_ENV`.

Running the release script can be run on bare metal, but this might result in side effects as certain extensions or PHP version do not match up with versions used in the containers.

To have reproducible results, run the release script in your container:

`docker run -v ~/Releases/:/root/Releases/ -v $PWD/bin/makeRelease.sh:/root/Releases/makeRelease.sh ghcr.io/openconext/openconext-containers/openconext-phpfpm-dev:latest /root/Releases/makeRelease.sh`

Note that the release script is run automatically on pushing a new semver tag to Github. The release script builds the release artifacts and uploads them to the release page.

## Texts and translations

When adding translatable keys, the easiest way to work is to add them in the twig templates first (eg. `'some.key'|trans`) and then add them to the translations files (see `<root>/translations`).

### Overriding translations

Translations can be overriden by adding an `overrides.<lang>.php` file in the `<root> /translations/overrides` folder.  A sample content of such a file:
```php
<?php

return [
    'general' => [
        'suite_name' => 'Unseen university',
    ]
];
```

This would override the `general.suite_name` key.

The suite name and the organisation noun can be used as 'magic' translation parameters in the translation files. This
can be achieved by using `%suiteName%` and `%organisationNoun%` in your translations. These translations are 
automatically replaced in the i18n Twig extension.

#### Example:

In your translation file (php based in this case)
```php
'general' => [
    'suite_name' => 'Unseen university',
    'organisation_noun' => 'library',
]
'introduction' => 'With %suiteName% you login with all different services used by your %organisationNoun%';
```

In your twig template:
```twig
<p>{{ 'introduction'|trans }}</p> {# Note that no suiteName or orgNoun translation parameters are passed! #}
```

Results in the following translation

`With Unseen university you login with all different services used by your library`


## Common tasks

### Running tests
QA tooling is installed for this project. A collection of code quality checks can be performed using the

`composer run tests` 

command. These tests will also be run when GitHub Actions runs the test-integration workflow.

Individual tests can be run using the shell scripts that are found in the `ci` folder.

### Working on the front-end

* We use the Twig template engine. See the excellent Twig documentation to get started with Twig.
* Symfony Webpack Encore is used to build/pack/minify/optimize the front-end resources.
   * After installation of Profile, please run `npm install` to pull all the build tooling and front-end dependencies.
   * Building the front-end can be done by running: `npm run encore dev` (for development, includes debug friendly features like sourcemaps)and `npm run build` for a production build.
   * Watch mode can be used, by using `npm run watch`, your mileage may vary as we are developing on a remote machine and fs changes might be noticed with a noticeable delay.
* Front-end dependencies are tracked using NPM. Enabling us to keep track of known vulnerabilities and making updating packages easier. Feel free to use Yarn as an alternative, but please do not commit the Yarn lockfile as we chose to use the NPM solution.
* Dialects: we use [SASS](https://sass-lang.com/) and [vanilla JavaScript](https://vanilla.js.org/).

### Add support for new Attribute Aggregation source
In EngineBlock ARP, attributes can be derived from a source other than the IdP. Whenever a source other than
the IdP is configured. Profile will not (yet) attempt to retrieve the value for that attribute. But will show only a
summation of the attribute names for each given source. 

When a new source is added in the Service Registry (or Manage) it must also be added to Profile.  
1. Add translation entry in `translations.html.twig`. At the bottom of the file:
    ```twig
    {{ 'profile.table.source_description.voot'|trans }}
    {{ 'profile.table.source_description.orcid'|trans }}
    {{ 'profile.table.source_description.sab'|trans }}
    
    {# Add your new source here, make sure the source name complies with the sourcename specified in the service registry. #}
    ```
2. Extract the new translation and translate them in the available `messages.LANG.yml` translation files.
3. Done.

To test your change. Modify one of the SP's already present in the 'My Services' overview with the newly added source. 
Do this by changing the source of one of the attributes to the newly added source. You might need to add the source to 
the SR/Manage configuration first. 

# License
This project is licensed under version 2.0 of the Apache License, as described
in the file [LICENSE](LICENSE).

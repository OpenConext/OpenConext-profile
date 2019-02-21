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
- PHP 5.6
- EngineBlock 5.6 >= 5.6.7 
- EngineBlock 5.7 >= 5.7.1
- EngineBlock must be configured to release an unspecified NameID to Profile

## Development
To setup your development environment, run `vagrant up` in the project directory.
Make sure an IdP (OpenConext Engineblock) is configured and running correctly. Do 
so by using the installation instructions found in the [Openconext-Deploy repository](https://github.com/OpenConext/OpenConext-deploy/blob/master/README.md).

After installing OpenConext-deploy, make sure your Profile installation is 
registered in the Service registry or Manage. To do so follow the instructions 
below.

! Note that there now are two active OpenConext-profile installations, one development
version: https://profile-dev.vm.openconext.org and the OpenConext-deploy pre-installed
version available at: https://profile.vm.openconext.org.

In order for the profile VM to be able to access the OpenConext-deploy
VM, you need to modify the hosts file of the profile VM and point the
EngineBlock and aggregator (AA) hostnames to the loadbalancer VM:

    192.168.66.98 engine-api.vm.openconext.org aa.vm.openconext.org

### Configure Profile as SP in service registry

 1. Visit https://manage.vm.openconext.org/
 2. Enter username 'admin' on the mujina IDP login form (password also 'admin')
 3. Click 'Import from XML'
 4. Enter Entity ID: `https://profile-dev.vm.openconext.org/authentication/metadata`
 5. Use the following URL: `https://profile-dev.vm.openconext.org/authentication/metadata`
 7. Click 'Create'
 8. Then set the state to 'Production'
 9. Repeat steps 4 to 8 with Connection ID and entity url: `https://profile-dev.vm.openconext.org/app_dev.php/authentication/metadata`
 
You should now be able to successfully login!

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
personal data stored by SURFconext. To enable the API add the following configuration to the `parameters.yml`

```yaml
    user_lifecycle_enabled: true
    user_lifecycle_api_base_url: 'https://user-lifecycle.example.com/' # the application knows the location of the endpoint
    user_lifecycle_api_username: userlifecycle
    user_lifecycle_api_password: secret
    user_lifecycle_api_verify_ssl: false
``` 

Make sure to fill all parameters, also when the `user_lifecycle_enabled` toggle is disabled.

See the User Lifecycle project on [GitHub](https://github.com/OpenConext/OpenConext-user-lifecycle) for more information.

## Releases
`RMT` is used for tagging releases. Run `./RMT release` to tag a release.  Make
sure you are on the `master` branch and don't have any changes before tagging
a release.

## Deployment
Run `./makeRelease.sh` with the version number of the relevant release to create a deployable tar-ball.

During deployment, unpack the tar on the deployment target and configure the
application by placing the required `parameters.yml` and
`global_view_parameters.yml` files in the `app/config` directory.
To prepare the application environment, run `composer prepare-env` on the
deployment target.

Make sure to set the correct Symfony environment by setting or exporting
`SYMFONY_ENV`.

## Texts and translations
Updating the texts (and translations of those texts) in the web interface
can be done on an installation that runs in `DEV` mode. Make sure you log
into profile at `https://profile-dev.vm.openconext.org`. Then go to
`https://profile.vm.openconext.org/app_dev.php/_trans/` to update the strings.

The following command can be used to scan for translations:

     ./bin/extract-translations.sh

## Common tasks

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
2. Extract the new translation and translate them in the available `messages.LANG.xliff` translation files.
3. Done.

To test your change. Modify one of the SP's already present in the 'My Services' overview with the newly added source. 
Do this by changing the source of one of the attributes to the newly added source. You might need to add the source to 
the SR/Manage configuration first. 

# License
This project is licensed under version 2.0 of the Apache License, as described
in the file [LICENSE](LICENSE).

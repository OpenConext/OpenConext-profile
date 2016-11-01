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
- EngineBlock >= 5.1.0

## Development
To setup your development environment, run `vagrant up` in the project directory.
Make sure an IdP (OpenConext Engineblock) is configured and running correctly.

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
into profile at `https://profile.<yourdomain>`. Then go to
`https://profile.<yourdomain>/app_dev.php/_trans/` to update the strings.

# License
This project is licensed under version 2.0 of the Apache License, as described
in the file [LICENSE](LICENSE).

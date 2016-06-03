OpenConext-profile
==================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/OpenConext/OpenConext-profile/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/OpenConext/OpenConext-profile/?branch=develop)

[![Build Status](https://travis-ci.org/OpenConext/OpenConext-profile.svg)](https://travis-ci.org/OpenConext/OpenConext-profile)

## Development
To setup your development environment, run `vagrant up` in the project directory.

## Releases
`RMT` is used for tagging releases. Run `./RMT release` to tag a release. 
Make sure you are on the `master` branch and don't have any changes before tagging a release.

## Deployment
Run `./makeRelease.sh` with the version number of the relevant release to create a deployable tar-ball.
During deployment, unpack the tar on the deployment target and configure the application by placing the required 
`parameters.yml` and `global_view_parameters.yml` files in the `app/config` directory.
To prepare the application environment, run `composer prepare-env` on the deployment target.

Make sure to set the correct Symfony environment by setting or exporting `SYMFONY_ENV`.

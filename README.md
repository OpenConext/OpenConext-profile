OpenConext-profile
==================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/OpenConext/OpenConext-profile/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/OpenConext/OpenConext-profile/?branch=develop)

[![Build Status](https://travis-ci.org/OpenConext/OpenConext-profile.svg)](https://travis-ci.org/OpenConext/OpenConext-profile)

# Setup Development Virtual Machine
To start the virtual machine, use the `vagrant up` command in the project root directory. The machine will be
provisioned if necessary. Before provisioning, generate and add the necessary key and certificate (see below).

Make sure the identity provider you are using is reachable from the virtual machine.

## HTTPS
In order for HTTPS to be setup properly, a private key (`server.key`) and a certificate (`server.crt`) should be present
inside the `project_root/ansible/ssl` directory so they can be copied during provisioning and used by the web server.

Use the following commands to generate the required key and certificate:

    # generate private key
    openssl genrsa -des3 -out server.key 2048
    
    # create certificate signing request
    openssl req -new -key server.key -out server.csr
    
    # remove passphrase from key
    cp server.key server.key.org
    openssl rsa -in server.key.org -out server.key
    
    # sign ssl certificate
    openssl x509 -req -days 365 -in server.csr -signkey server.key -out server.crt

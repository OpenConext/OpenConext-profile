#!/bin/sh
./vendor/bin/phpcs --report=full --standard=./phpcs.xml --warning-severity=0 --extensions=php --ignore="*/Tests/*" ./src

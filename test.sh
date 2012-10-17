#!/bin/bash
set -e

if [ ! -f composer.phar ]; then
    curl http://getcomposer.org/installer | php
fi

if [ ! -d vendor ]; then
    php composer.phar install --prefer-source
fi

phpunit

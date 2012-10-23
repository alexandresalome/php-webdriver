<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../vendor/autoload.php';

$client  = new WebDriver\Client('http://localhost:4444/wd/hub');
$browser = $client->createBrowser('firefox');

$browser->open('http://google.fr');
$browser->open('http://google.pl');
$browser->open('http://google.pt');

$browser->back();

echo sprintf("The current URL is: %s\n", $browser->getUrl());

$browser->back();

$browser->forward();
$browser->forward();
$browser->forward();

echo sprintf("The current URL is: %s\n", $browser->getUrl());

$browser->close();

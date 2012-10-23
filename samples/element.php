<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * This sample shows the error message when asking a not-existing browser
 */

require_once __DIR__.'/../vendor/autoload.php';

use WebDriver\Client;
use WebDriver\By;

$client  = new Client('http://localhost:4444/wd/hub');
$session = $client->createBrowser('firefox');

$session->open('http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element');
$title = $session->element(By::tag('title'));

echo $title->text();

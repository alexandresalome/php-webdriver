<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * This sample will capture a screenshot of google and output it to a file
 */

require_once __DIR__.'/../vendor/autoload.php';

$target = __DIR__.'/screenshot.png';

$client  = new WebDriver\Client('http://localhost:4444/wd/hub');
$session = $client->createSession('firefox');

$session->open('http://google.fr');
$session->open('http://google.co.uk');
$session->open('http://google.pl');
$session->open('http://google.pt');

$session->back();
$session->back();
$session->back();
$session->forward();
$session->forward();
$session->forward();

$session->close();

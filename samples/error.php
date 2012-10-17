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

$client  = new WebDriver\Client('http://localhost:4444/wd/hub');
$capabilities = new WebDriver\Capabilities(4000);

try {
    $session = $client->createSession($capabilities);
} catch (\RuntimeException $e) {
    echo sprintf("Error message: %s\n", $e->getMessage());
}

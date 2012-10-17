<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Tests;

use Buzz\Client\Mock\FIFO;
use Buzz\Client\ClientInterface;
use Buzz\Message\RequestInterface;
use Buzz\Message\MessageInterface;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class BuzzClientFIFO implements ClientInterface
{
    /**
     * The last request executed
     *
     * @var Buzz\Message\RequestInterface
     */
    protected $lastRequest;

    /**
     * Queue of pending responses.
     *
     * @var array
     */
    protected $queue = array();

    /**
     * @inheritdoc
     */
    public function send(RequestInterface $request, MessageInterface $response)
    {
        $this->lastRequest = $request;
        $pop = array_pop($this->queue);

        if (!$pop) {
            throw new \LogicException('Nothing to pop');
        }

        $response->setHeaders($pop->getHeaders());
        $response->setContent($pop->getContent());
    }

    public function sendToQueue(MessageInterface $response)
    {
        $this->queue[] = $response;
    }

    /**
     * Returns the last request.
     *
     * @return Buzz\Message\Request
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    public function getQueue()
    {
        return $this->queue;
    }
}

<?php

/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Exception;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class ExceptionFactory
{
    const STATUS_SUCCESS                      = 0;
    const STATUS_NO_SUCH_ELEMENT              = 7;
    const STATUS_NO_SUCH_FRAME                = 8;
    const STATUS_UNKNOWN_COMMAND              = 9;
    const STATUS_STALE_ELEMENT_REFERENCE      = 10;
    const STATUS_ELEMENT_NOT_VISIBLE          = 11;
    const STATUS_INVALID_ELEMENT_STATE        = 12;
    const STATUS_UNKNOWN_ERROR                = 13;
    const STATUS_ELEMENT_IS_NOT_SELECTABLE    = 15;
    const STATUS_JAVASCRIPT_ERROR             = 17;
    const STATUS_XPATH_LOOKUP_ERROR           = 19;
    const STATUS_TIMEOUT                      = 21;
    const STATUS_NO_SUCH_WINDOW               = 23;
    const STATUS_INVALID_COOKIE_DOMAIN        = 24;
    const STATUS_UNABLE_TO_SET_COOKIE         = 25;
    const STATUS_UNEXPECTED_ALERT_OPEN        = 26;
    const STATUS_NO_ALERT_OPEN_ERROR          = 27;
    const STATUS_SCRIPT_TIMEOUT               = 28;
    const STATUS_INVALID_ELEMENT_COORDINATES  = 29;
    const STATUS_IME_NOT_AVAILABLE            = 30;
    const STATUS_IME_ENGINE_ACTIVATION_FAILED = 31;
    const STATUS_INVALID_SELECTOR             = 32;

    static public function createExceptionFromArray(array $array)
    {
        $status  = $array['status'];
        $message = $array['value']['message'];

        if ($status == self::STATUS_NO_SUCH_ELEMENT) {
            return new NoSuchElementException($message);
        } elseif ($status == self::STATUS_ELEMENT_NOT_VISIBLE) {
            return new ElementNotVisibleException($message);
        } elseif ($status == self::STATUS_UNKNOWN_ERROR) {
            return new UnknownException($message);
        } elseif ($status == self::STATUS_INVALID_SELECTOR) {
            return new InvalidSelectorException($message);
        } elseif ($status == self::STATUS_INVALID_COOKIE_DOMAIN) {
            return new InvalidCookieDomainException($message);
        } elseif ($status == self::STATUS_UNEXPECTED_ALERT_OPEN) {
            return new UnexpectedAlertOpenException($message);
        } elseif ($status == self::STATUS_NO_ALERT_OPEN_ERROR) {
            return new NoAlertOpenErrorException($message);
        } elseif ($status == self::STATUS_SCRIPT_TIMEOUT) {
            return new ScriptTimeoutException($message);
        }

        throw new LibraryException(sprintf('An error occured: #%s: %s', $status, $message));
    }
}

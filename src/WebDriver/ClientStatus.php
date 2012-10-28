<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class ClientStatus
{
    protected $sessionId;
    protected $status;
    protected $osArchitecture;
    protected $osName;
    protected $osVersion;
    protected $javaVersion;
    protected $buildRevision;
    protected $buildTime;
    protected $buildVersion;

    static public function fromArray($array)
    {
        $status = new ClientStatus();

        $status->sessionId      = isset($array['sessionId']) ? $array['sessionId'] : null;
        $status->status         = isset($array['status']) ? $array['status'] : null;
        $status->osArchitecture = isset($array['value']['os']['arch']) ? $array['value']['os']['arch'] : null;
        $status->osName         = isset($array['value']['os']['name']) ? $array['value']['os']['name'] : null;
        $status->osVersion      = isset($array['value']['os']['version']) ? $array['value']['os']['version'] : null;
        $status->javaVersion    = isset($array['value']['java']['version']) ? $array['value']['java']['version'] : null;
        $status->buildRevision  = isset($array['value']['build']['revision']) ? $array['value']['build']['revision'] : null;
        $status->buildTime      = isset($array['value']['build']['time']) ? $array['value']['build']['time'] : null;
        $status->buildVersion   = isset($array['value']['build']['version']) ? $array['value']['build']['version'] : null;

        return $status;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getOs()
    {
        return sprintf('%s %s (arch: %s)', $this->osName, $this->osVersion, $this->osArchitecture);
    }

    public function getOsArchitecture()
    {
        return $this->osArchitecture;
    }

    public function getOsName()
    {
        return $this->osName;
    }

    public function getOsVersion()
    {
        return $this->osVersion;
    }

    public function getJavaVersion()
    {
        return $this->javaVersion;
    }

    public function getBuildRevision()
    {
        return $this->buildRevision;
    }

    public function getBuildTime()
    {
        return $this->buildTime;
    }

    public function getBuildVersion()
    {
        return $this->buildVersion;
    }
}

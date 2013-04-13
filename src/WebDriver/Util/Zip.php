<?php

namespace WebDriver\Util;

use WebDriver\Exception\LibraryException;
use Symfony\Component\Process\ProcessBuilder;

class Zip
{
    private $path;

    /**
     * Instanciates a zip archive object.
     *
     * @param string $path Path to the zip archive to create or update
     */
    public function __construct($path = null)
    {
        $this->path = $path ?: $this->createTempPath();
    }

    public function addFile($file)
    {
        $args    = array('zip', '-j', $this->path, $file);
        $process = ProcessBuilder::create($args)->getProcess();

        $process->run();

        if (!$process->isSuccessful()) {
            throw new LibraryException('Error while running zip command: '.$process->getErrorOutput());
        }
    }

    public function getContent()
    {
        return file_get_contents($this->path);
    }

    /**
     * Returns path to the zipfile.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns a temporary not-existing path.
     *
     * @return string
     */
    protected function createTempPath()
    {
        $file = tempnam(sys_get_temp_dir(), 'webdriver_');
        unlink($file);

        return $file.'.zip';
    }
}

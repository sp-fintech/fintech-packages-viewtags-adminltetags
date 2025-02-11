<?php

namespace apps\Fintech\Packages\Adminltetags\Install;

use System\Base\BasePackage;

class Install extends BasePackage
{
    public function install()
    {
        $this->preInstall();

        $this->installDb();

        $this->postInstall();

        return true;
    }

    public function preInstall()
    {
        return true;
    }

    public function installDb()
    {
        return true;
    }

    public function postInstall()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }
}
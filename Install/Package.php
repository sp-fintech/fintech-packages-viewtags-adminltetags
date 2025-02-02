<?php

namespace apps\Fintech\Packages\Adminltetags\Install;

use System\Base\BasePackage;

class Package extends BasePackage
{
    protected $installer;

    protected $modulesToInstallOrUpdate;

    public function install($installer)
    {
        $this->installer = $installer;

        $this->modulesToInstallOrUpdate = $this->installer->modulesToInstallOrUpdate;

        $this->preInstall();

        $this->installDb();

        $this->postInstall();

        return true;
    }

    protected function preInstall()
    {
        return true;
    }

    protected function installDb()
    {
        return true;
    }

    protected function postInstall()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }
}
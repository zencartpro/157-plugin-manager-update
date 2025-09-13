<?php
/**
 * Zen Cart German Specific (210 code in 157)
 * @copyright Copyright 2003-2025 Zen Cart Development Team
 * Zen Cart German Version - www.zen-cart-pro.at
 * @copyright Portions Copyright 2003 osCommerce
 * @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
 * @version $Id: Installer.php for newer plugins 2025-09-12 15:54:16Z webchills $
 */

namespace Zencart\PluginSupport;

class Installer
{
    protected string $pluginDir;
    protected string $pluginKey;
    protected string $version;
    protected ?string $oldVersion;

    public function __construct(protected SqlPatchInstaller $patchInstaller, protected ScriptedInstallerFactory $scriptedInstallerFactory, protected PluginErrorContainer $errorContainer)
    {
    }

    public function setVersions(string $pluginDir, string $pluginKey, string $version, ?string $oldVersion = null): void
    {
        $this->pluginDir = $pluginDir;
        $this->pluginKey = $pluginKey;
        $this->version = $version;
        $this->oldVersion = $oldVersion;
    }

    public function getVersionInformation(): array
    {
        return [
            'pluginKey' => $this->pluginKey,
            'pluginDir' => $this->pluginDir,
            'version' => $this->version,
            'oldVersion' => $this->oldVersion,
        ];
    }

    public function executeInstallers($pluginDir): void
    {
        $this->executePatchInstaller($pluginDir);
        if ($this->errorContainer->hasErrors()) {
            return;
        }
        $this->executeScriptedInstaller($pluginDir);
    }

    public function executeUninstallers($pluginDir): void
    {
        $this->executePatchUninstaller($pluginDir);
        if ($this->errorContainer->hasErrors()) {
            return;
        }
        $this->executeScriptedUninstaller($pluginDir);
    }

    public function executeUpgraders($pluginDir, $oldVersion): void
    {
        $this->executeScriptedUpgrader($pluginDir, $oldVersion);
    }

    protected function executePatchInstaller($pluginDir): void
    {
        $patchFile = 'install.sql';
        $this->executePatchFile($pluginDir, $patchFile);
    }

    protected function executePatchUninstaller($pluginDir): void
    {
        $patchFile = 'uninstall.sql';
        $this->executePatchFile($pluginDir, $patchFile);
    }

    protected function executePatchFile($pluginDir, $patchFile): void
    {
        if (!file_exists($pluginDir . '/Installer/' . $patchFile)) {
            return;
        }
        $lines = file($pluginDir . '/Installer/' . $patchFile);
        $paramLines = $this->patchInstaller->parse($lines);
        if ($this->errorContainer->hasErrors()) {
            return;
        }
        $this->patchInstaller->executePatchSql($paramLines);
    }

    protected function executeScriptedInstaller($pluginDir): void
    {
        if (!file_exists($pluginDir . '/Installer/ScriptedInstaller.php')) {
            return;
        }
        $scriptedInstaller = $this->scriptedInstallerFactory->make($pluginDir);
        $scriptedInstaller->setVersionDetails($this->getVersionInformation());
        $scriptedInstaller->doInstall();
    }

    protected function executeScriptedUninstaller($pluginDir): void
    {
        if (!file_exists($pluginDir . '/Installer/ScriptedInstaller.php')) {
            return;
        }
        $scriptedInstaller = $this->scriptedInstallerFactory->make($pluginDir);
        $scriptedInstaller->setVersionDetails($this->getVersionInformation());
        $scriptedInstaller->doUninstall();
    }

    protected function executeScriptedUpgrader($pluginDir, $oldVersion): void
    {
        if (!file_exists($pluginDir . '/Installer/ScriptedInstaller.php')) {
            return;
        }
        $scriptedInstaller = $this->scriptedInstallerFactory->make($pluginDir);
        $scriptedInstaller->setVersionDetails($this->getVersionInformation());
        $scriptedInstaller->doUpgrade($oldVersion);
    }

    public function getErrorContainer(): PluginErrorContainer
    {
        return $this->errorContainer;
    }
}

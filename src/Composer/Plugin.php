<?php

namespace Kunstmaan\CodeQuality\Composer;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Symfony\Component\Filesystem\Filesystem;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    private const PACKAGE_NAME = 'kunstmaan/code-quality-config';

    /** @var bool */
    private $hasCopiedFiles = false;

    /** @var Filesystem */
    private $fs;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL => 'postPackageInstall',
            ScriptEvents::POST_INSTALL_CMD => 'postInstall',
            ScriptEvents::POST_UPDATE_CMD => 'postInstall',
        ];
    }

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->fs = new Filesystem();
    }

    public function postPackageInstall(PackageEvent $event)
    {
        $operation = $event->getOperation();
        if ($operation instanceof InstallOperation && $operation->getPackage()->getName() === self::PACKAGE_NAME) {
            $vendorPath = $event->getComposer()->getConfig()->get('vendor-dir');
            $to = dirname($vendorPath);

            if (!$this->fs->exists($to . '/.php_cs')) {
                $this->fs->copy(dirname(__DIR__, 2) . '/files/.php_cs.dist', $to . '/.php_cs');
                $this->fs->copy(dirname(__DIR__, 2) . '/files/grumphp.yml.dist', $to . '/grumphp.yml');

                $this->hasCopiedFiles = true;
            }
        }
    }

    public function postInstall(Event $event)
    {
        if ($this->hasCopiedFiles) {
            $event->getIO()->write(file_get_contents(__DIR__ . '/post-install.txt'));
        }
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
    }
}

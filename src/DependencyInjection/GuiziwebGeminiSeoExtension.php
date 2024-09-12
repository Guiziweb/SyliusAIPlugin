<?php

declare(strict_types=1);

namespace Guiziweb\GeminiSeoPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;

final class GuiziwebGeminiSeoExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    /** @psalm-suppress UnusedVariable */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));

        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependDoctrineMigrations($container);
        $this->prependDoctrineMappings($container);
    }

    private function prependDoctrineMappings(ContainerBuilder $container): void
    {
        /** @var array<string, array<string, string>> $metadata */
        $metadata = $container->getParameter('kernel.bundles_metadata');

        $config = array_merge(...$container->getExtensionConfig('doctrine'));

        if (!isset($config['dbal']) || !isset($config['orm'])) {
            return;
        }

        $rootPathToPlugin = $metadata['GuiziwebGeminiSeoPlugin']['path'];

        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'mappings' => [
                    'GuiziwebGeminiSeoPlugin' => [
                        'type' => 'yml',
                        'dir' => $rootPathToPlugin . '/config/doctrine/',
                        'is_bundle' => false,
                        'prefix' => 'Guiziweb\GeminiSeoPlugin\Entity',
                        'alias' => 'GuiziwebGeminiSeo',
                    ],
                ],
            ],
        ]);
    }

    protected function getMigrationsNamespace(): string
    {
        return 'DoctrineMigrations';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@GuiziwebGeminiSeoPlugin/src/Migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return [
            'Sylius\Bundle\CoreBundle\Migrations',
        ];
    }
}

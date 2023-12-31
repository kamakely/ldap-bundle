<?php

/*
 * This file is part of the TounafLdapBundle package.
 *
 * (c) Fetraharinjatovo Nambinina <harinjatovo.fetra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tounaf\Ldap\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Tounaf\Ldap\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Tounaf\Ldap\DependencyInjection\Factory\ConfiguratorFactoryInterface;

class TounafLdapExtension extends Extension implements PrependExtensionInterface
{
    private array $factories = [];
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
        $this->getSecurityExtensionConfig($container);
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);
        if($container->hasDefinition('Symfony\Component\Ldap\Adapter\ExtLdap\Adapter') && isset($config['connection']) ) {
            $container->getDefinition('Symfony\Component\Ldap\Adapter\ExtLdap\Adapter')->setArguments([$config['connection']]);
        }
    }

    public function getConfiguration(array $configs, ContainerBuilder $container): ?ConfigurationInterface
    {
        return new Configuration($this->factories);
    }

    public function prepend(ContainerBuilder $container)
    {
        $ldapConf = $container->getExtensionConfig('tounaf_ldap');
        if($container->hasExtension('security')) {
            $ldapProviderName = "ldap";
            
            if(count($ldapConf) > 0 && isset($ldapConf[0]['providers'])) {
                $providerConfig = [
                    'providers' => $ldapConf[0]['providers']
                ];
                
                $ldapProviderName = array_keys($ldapConf[0]['providers']);
                $ldapProviderName = array_shift($ldapProviderName);
                if(!isset($providerConfig['providers'][$ldapProviderName]['ldap']['service'])) {
                    $providerConfig['providers'][$ldapProviderName]['ldap']['service'] = "Symfony\Component\Ldap\Ldap";
                }

                if(!isset($providerConfig['providers'][$ldapProviderName]['ldap']['default_roles'])) {
                    $providerConfig['providers'][$ldapProviderName]['ldap']['default_roles'] = ["ROLE_USER"];
                }
                
                $container->prependExtensionConfig('security', $providerConfig);
            }

            if(count($ldapConf[0]) > 0 && isset($ldapConf[0]['form_login_ldap'])) {
                $container->loadFromExtension('security', [
                    'firewalls' => [
                        'main' => [
                            'provider' => $ldapProviderName,
                            'form_login_ldap' => array_merge(["service" => "Symfony\Component\Ldap\Ldap"], $ldapConf[0]['form_login_ldap'])
                        ]
                    ]
                ]);
            }
            
        }
        

    }

    public function addConfigurator(ConfiguratorFactoryInterface $configurator)
    {
        $this->factories[] = $configurator;
    }

    private function getSecurityExtensionConfig(ContainerBuilder $container)
    {
        if($container->hasExtension('security')) {
            $extension = $container->getExtension('security');
            $configs = $container->getExtensionConfig('security');
            $extension = $container->getExtension('security');
            $configuration = $extension->getConfiguration($configs, $container);
            return (new Processor())->processConfiguration($configuration, $configs);
        }
        
    }
}
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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function __construct(private array $factories)
    {
        
    }
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder("tounaf_ldap");
        $rootNode = $treeBuilder->getRootNode();
        $this->addConnectionLdapSection($rootNode);
        $this->addProviderLdapSection($rootNode);
        $this->addFormLoginLdapSection($rootNode);


        return $treeBuilder;
    }

    private function addConnectionLdapSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('connection')
                    ->children()
                        ->scalarNode('host')
                            ->isRequired()
                            ->info("Ldap host")
                        ->end()
                        ->scalarNode("port")
                            ->isRequired()
                            ->defaultValue('389')
                            ->info('The port used by ldap')
                        ->end()
                        ->scalarNode("encryption")
                            ->defaultValue('none')
                            ->info('the encryption method: none/tls/ssl')
                        ->end()
                        ->arrayNode('options')
                            ->children()
                                ->scalarNode("protocol_version")
                                    ->defaultValue("3")
                                    ->info("Verstion of protocole")
                                ->end()
                                ->booleanNode("referrals")
                                    ->defaultValue(false)
                                    ->info("Verstion of protocole")
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addProviderLdapSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode("providers")
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->arrayNode('ldap')
                                ->children()
                                    ->scalarNode("service")
                                        ->defaultValue("Symfony\Component\Ldap\Ldap")
                                    ->end()
                                    ->scalarNode("base_dn")
                                        ->isRequired()
                                        ->example("OU=Utilisateurs,DC=makeitpulse,DC=in,DC=axian-group,DC=com")
                                    ->end()
                                    ->scalarNode("search_dn")
                                        ->isRequired()
                                        ->example("CN=ogc-ass,OU=Service accounts,OU=Administrations,DC=ass,DC=in,DC=axian-group,DC=com")
                                    ->end()
                                    ->scalarNode("search_password")
                                        ->defaultNull()
                                        ->isRequired()
                                        ->example("your_favorit_password")
                                    ->end()
                                    ->scalarNode("default_roles")
                                        ->defaultValue("ROLE_USER")
                                        ->isRequired()
                                        ->example("ROLE_USER")
                                    ->end()
                                    ->scalarNode("uid_key")
                                        ->defaultValue("sAMAccountName")
                                        ->example("sAMAccountName")
                                    ->end()
                                    ->arrayNode('extra_fields')
                                        ->scalarPrototype()->example(['mail','name','username'])->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end() 
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addFormLoginLdapSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('form_login_ldap')
                    ->children()
                        ->scalarNode('dn_string')
                            ->isRequired()
                            ->defaultValue('{username}')
                            ->example('DOMAINE\{username}')
                            ->info('ds_string peut est de type: DOMAIN\{username} ou simplement {username}')
                        ->end()
                        ->scalarNode('login_path')
                            ->defaultValue('app_login')
                            ->info('route to login user')
                        ->end()
                        ->scalarNode('check_path')
                            ->defaultValue('app_login')
                            ->info('route to process login')
                        ->end()
                        ->scalarNode('default_target_path')
                            ->info('route to redirect user when login success')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
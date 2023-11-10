<?php

/*
 * This file is part of the TounafLdapBundle package.
 *
 * (c) Fetraharinjatovo Nambinina <harinjatovo.fetra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tounaf\Ldap\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class LdapServicePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('Symfony\Component\Ldap\Adapter\ExtLdap\Adapter')) {
            $definition = new Definition('Symfony\Component\Ldap\Adapter\ExtLdap\Adapter');
            $arguments = [
                [
                    'host' => 'localhost',
                    'port' => 389,
                    'options' => [
                        'protocol_version' => 3,
                        'referrals' => true,
                    ],
                ],
            ];
            $definition->setArguments($arguments);
            // Register the service definition
            $container->setDefinition('Symfony\Component\Ldap\Adapter\ExtLdap\Adapter', $definition);
        }

        if (!$container->hasDefinition('Symfony\Component\Ldap\Ldap')) {
            // Define the service
            $ldapDefinition = new Definition('Symfony\Component\Ldap\Ldap');
            $ldapDefinition->setArguments([new Reference('Symfony\Component\Ldap\Adapter\ExtLdap\Adapter')]);
            $ldapDefinition->addTag('ldap');
            $container->setDefinition('Symfony\Component\Ldap\Ldap', $ldapDefinition);
        }

    }
}
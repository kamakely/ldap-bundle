<?php

/*
 * This file is part of the TounafLdapBundle package.
 *
 * (c) Fetraharinjatovo Nambinina <harinjatovo.fetra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tounaf\Ldap;

use Tounaf\Ldap\DependencyInjection\TounafLdapExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tounaf\Ldap\DependencyInjection\Compiler\LdapServicePass;
use Tounaf\Ldap\DependencyInjection\Factory\SecurityDefinitionFactory;

class TounafLdapBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        /** @var TounafLdapExtension $extension */
        $extension = $container->getExtension('tounaf_ldap');
        $extension->addConfigurator(new SecurityDefinitionFactory());
        $container->addCompilerPass(new LdapServicePass());
    }
}
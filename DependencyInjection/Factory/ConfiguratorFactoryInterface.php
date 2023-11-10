<?php

namespace Tounaf\Ldap\DependencyInjection\Factory;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;

interface ConfiguratorFactoryInterface
{
    public function addDefinition(NodeDefinition $builder);
}
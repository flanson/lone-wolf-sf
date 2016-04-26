<?php
/**
 * Created by PhpStorm.
 * User: Grumly
 * Date: 24/04/2016
 * Time: 11:24
 */

namespace LoneWolfAppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
//        $rootNode = $treeBuilder->root('lone_wolf_app');
        $treeBuilder->root('lone_wolf_app');
        return $treeBuilder;
    }
}

<?php

namespace Flexix\MapperBundle\Util;

use Flexix\MapperBundle\Exceptions\MoreThanOneEntityClassForAliasException;
use Flexix\MapperBundle\Exceptions\NoAliasForEntityClassException;
use Flexix\MapperBundle\Exceptions\NoEntityClassForAliasException;
use Flexix\MapperBundle\Util\EntityMapperInterface;

/**
 * cache
 */
class EntityMapper  implements EntityMapperInterface{

    protected $bundles;

    public function __construct($bundles) {
        $this->bundles = $bundles;
    }

    public function getEntityClass($alias) {

       
        $results = [];

        foreach ($this->bundles as $bundle) {
            $result = $this->findEntityClass($alias, $bundle);

            if ($result) {
                $results[] = $result;
            }
        }

        $count = count($results);

        if ($count == 0) {
            throw new NoEntityClassForAliasException(sprintf('There is no entity class for alias: "%s" configured ', $alias));
        } else if ($count > 1) {
            throw new MoreThanOneEntityClassForAliasException(sprintf('There is more than one entity for alias: %s - %s', $alias, implode(',', $results)));
        } else {
            return $results[0];
        }
    }

    protected function findEntityClass($alias,$bundle) {

        
        foreach ($bundle as $map) {
            if ($map['alias'] == $alias) {
                return $map['class'];
            }
        }
    }

    public function getAlias($entityClass) {

        $results = [];

        foreach ($bundles as $bundle) {
            $result = $this->findAlias($entityClass, $bundle);

            if ($result) {
                $results[] = $result;
            }
        }

        $count = count($results);

        if ($count == 0) {
            throw new NoAliasForEntityClassException(sprintf('There is no alias for entityClass: %s', $entityClass));
        } else {
            return $results[0];
        }
    }

    protected function findAlias($entityClass, $bundle) {

        foreach ($this->bundles[$bundle] as $map) {

            if ($map['class'] == $entityClass) {
                return $map['alias'];
            }
        }
    }
    
    
    public function getBundles() {

        return $this->bundles;
        
    }



}

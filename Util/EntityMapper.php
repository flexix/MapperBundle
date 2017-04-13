<?php

namespace Flexix\MapperBundle\Util;

use Flexix\MapperBundle\Exceptions\MoreThanOneEntityClassForAliasException;
use Flexix\MapperBundle\Exceptions\NoAliasForEntityClassException;
use Flexix\MapperBundle\Exceptions\NoEntityClassForAliasException;
use Flexix\MapperBundle\Exceptions\NoBundleException;

/**
 * cache
 */
class EntityMapper {

    protected $entities;

    public function __construct($entities) {
        $this->entities = $entities;
    }

    public function getEntityClass($alias, $bundles) {

        $this->checkNamespaceExists($bundles);
        $results = [];

        foreach ($bundles as $bundle) {
            $result = $this->findEntityClass($alias, $bundle);

            if ($result) {
                $results[] = $result;
            }
        }

        $count = count($results);

        if ($count == 0) {
            throw new NoEntityClassForAliasException(sprintf('There is no entity class for alias: "%s" configured ', $alias));
        } else if ($count > 1) {
            throw new MoreThanOneEntityClassForAliasException(sprintf('There is more than one entityClass for alias: %s - %s', $alias, implode(',', $results)));
        } else {
            return $results[0];
        }
    }

    protected function findEntityClass($alias, $bundle) {

        foreach ($this->entities[$bundle] as $map) {
            if ($map['alias'] == $alias) {
                return $map['entity_class'];
            }
        }
    }

    public function getAlias($entityClass, $bundles=[]) {

        $this->checkNamespaceExists($bundles);
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

        foreach ($this->entities[$bundle] as $map) {

            if ($map['entity_class'] == $entityClass) {
                return $map['alias'];
            }
        }
    }

    protected function checkNamespaceExists($bundles) {

        if (is_array($bundles)) {
            
            foreach ($bundles as $bundle) {

                if (!array_key_exists($bundle, $this->entities)) {
                    throw new NoBundleException(sprintf('There is no bundle in your mapper file: %s', $bundle));
                }
            }
        }
        else
        {
            throw new NoBundleException(sprintf('There are no bundles associated with your application, you shoud configure "applications" section in your mapper file first'));
        }
    }

}

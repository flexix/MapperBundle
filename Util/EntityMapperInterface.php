<?php

namespace Flexix\MapperBundle\Util;


class EntityMapperInterface {

    public function __construct($bundles); 

    public function getEntityClass($alias); 

    protected function findEntityClass($alias,$bundle);

    public function getAlias($entityClass);

    protected function findAlias($entityClass, $bundle);

}

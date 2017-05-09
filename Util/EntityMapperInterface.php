<?php

namespace Flexix\MapperBundle\Util;


interface EntityMapperInterface {

    public function __construct($bundles); 

    public function getEntityClass($alias); 

    public function getAlias($entityClass);


}

<?php

// Flexix\MapperBundle\Util\ApplicationMapper

namespace Flexix\MapperBundle\Util;

/**
 * cache
 */
class ApplicationMapper {

    protected $applications;

    public function __construct($applications=[]) {

        $this->applications = $applications;
    }

    public function getBundles($name) {

        if ($name) {

            foreach ($this->applications as $application) {

                if ($application["name"] == $name) {
                    return $application["bundles"];
                }
            }
        } else {
            
            throw new \Exception('Name not set');
        }
    }

}

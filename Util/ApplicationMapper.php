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

    public function getBundles($path) {

        if ($path) {

            foreach ($this->applications as $application) {

                if ($application["path"] == $path) {
                    return $application["bundles"];
                }
            }
        } else {
            
            throw new \Exception('Name not set');
        }
    }

}

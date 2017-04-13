<?php

namespace Flexix\MapperBundle\Tests\Util;

use Symfony\Component\Yaml\Yaml;
use Flexix\MapperBundle\Util\ApplicationMapper;
use \PHPUnit\Framework\TestCase;

/**
 * Description of EntityAnalyzer
 * php  app/phpunit.phar   --bootstrap=./app/autoload.php ./src/Flexix/MapperBundle/Tests/Util/ApplicationMapperTest.php
 * @author Mariusz Piela <mariuszpiela@tmsolution.pl>
 */
class ApplicationMapperTest extends TestCase {

    const _APPLICATION_NAME='admin';
    
    
   
    public static function setupBeforeClass() {
    }

    public function testGetBundles() {
        
        $mapperConfiguration = Yaml::parse(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'testMapper.yml'));
        $appliactionMapper=new ApplicationMapper($mapperConfiguration['flexix_mapper']['applications']);
        $bundles=$appliactionMapper->getBundles(self::_APPLICATION_NAME);
        $this->assertEquals(2,count($bundles));
        $this->assertEquals('flexix_entity_analyzer',$bundles[0]);
        
    }
    
   

}

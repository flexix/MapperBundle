<?php

namespace Flexix\MapperBundle\Generator;

use Symfony\Component\Yaml\Yaml;

class Mapper {

    protected $filePath;
    protected $manager;

    public function __construct($filePath, $manager) {
        $this->filePath = $filePath;
        $this->manager = $manager;
    }

    protected function getSnakeCase($text) {
        return ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $text)), '_');
    }

    protected function getDashCase($text) {
        return ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '-$0', $text)), '-');
    }

    protected function getBundleName($entityDirectoryNamespace) {
        $entityDirectoryNamespaceArr = explode('\\', $entityDirectoryNamespace);
        unset($entityDirectoryNamespaceArr[count($entityDirectoryNamespaceArr) - 1]);
        return substr(implode($entityDirectoryNamespaceArr), 0, -6);
    }

    protected function getEntityName($entityNamespace) {
        $entityNamespaceArr = explode('\\', $entityNamespace);
        return end($entityNamespaceArr);
    }

    protected function readEntities($root) {
        $allMetadata = $this->manager->getMetadataFactory()->getAllMetadata();

        if (!array_key_exists('flexix_mapper', $root)) {
            $root['flexix_mapper'] = [];
        }


        if (!array_key_exists('entities', $root['flexix_mapper']) || !is_array($root['flexix_mapper']['entities'])) {
            $root['flexix_mapper']['entities'] = [];
        }


        if (!array_key_exists('applications', $root['flexix_mapper']) || !is_array($root['flexix_mapper']['applications'])) {
            $root['flexix_mapper']['applications'] = [];
        }

        foreach ($allMetadata as $metadata) {

            $namespace = $this->getSnakeCase($this->getBundleName($metadata->namespace));

            if (!array_key_exists($namespace, $root['flexix_mapper']['entities'])) {
                $root['flexix_mapper']['entities'][$namespace] = [];
            }
            $entityName = $this->getEntityName($metadata->name);
            $snakeEntityName = $this->getSnakeCase($entityName);
            $root['flexix_mapper']['entities'][$namespace][$snakeEntityName]['alias'] = $this->getDashCase($entityName);
            $root['flexix_mapper']['entities'][$namespace][$snakeEntityName]['entity_class'] = $metadata->name;
        }
        $this->recursiveSort($root);
        return $root;
    }

    protected function recursiveSort(&$array) {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->recursiveSort($value);
            }
        }
        return ksort($array);
    }

    public function updateConfigFile() {

        if (file_exists($this->filePath)) {
            $root = Yaml::parse(file_get_contents($this->filePath));
        } else {
            $root=[];
        }
        $entities = $this->readEntities($root);
        $yaml = Yaml::dump($entities, 5);
        file_put_contents($this->filePath, $yaml);
    }

}

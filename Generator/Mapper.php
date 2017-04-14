<?php

namespace Flexix\MapperBundle\Generator;

use Symfony\Component\Yaml\Yaml;

class Mapper {

    protected $filePath;
    protected $manager;

    const REPLACE_MASK = '/[A-Z]([A-Z](?![a-z]))*/';

    public function __construct($filePath, $manager) {
        $this->filePath = $filePath;
        $this->manager = $manager;
    }

    protected function getSnakeCase($text) {
        return ltrim(strtolower(preg_replace(self::REPLACE_MASK, '_$0', $text)), '_');
    }

    protected function getDashCase($text) {
        return ltrim(strtolower(preg_replace(self::REPLACE_MASK, '-$0', $text)), '-');
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


        if (!array_key_exists('bundles', $root['flexix_mapper']) || !is_array($root['flexix_mapper']['bundles'])) {
            $root['flexix_mapper']['bundles'] = [];
        }

        foreach ($allMetadata as $metadata) {

            $namespace = $this->getSnakeCase($this->getBundleName($metadata->namespace));

            if (!array_key_exists($namespace, $root['flexix_mapper']['bundles'])) {
                $root['flexix_mapper']['bundles'][$namespace] = [];
            }

            $entityName = $this->getEntityName($metadata->name);
            $alias = $this->getDashCase($entityName);
            if ($this->checkAliasExists($root['flexix_mapper']['bundles'], $alias)) {
                $alias = sprintf('%s.%s', $this->getDashCase($this->getBundleName($metadata->namespace)), $alias);
            }


            $snakeEntityName = $this->getSnakeCase($entityName);
            $root['flexix_mapper']['bundles'][$namespace][$snakeEntityName]['alias'] = $alias;
            $root['flexix_mapper']['bundles'][$namespace][$snakeEntityName]['entity'] = $metadata->name;
        }
        $this->recursiveSort($root);
        return $root;
    }

    protected function checkAliasExists($bundles, $alias) {

        foreach ($bundles as $bundle => $entities) {
            foreach ($entities as $entity) {
                if (array_key_exists('alias', $entity) && $entity['alias'] = $alias) {
                    return true;
                }
            }
        }
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


        $root = Yaml::parse(file_get_contents($this->filePath));

        if (!$root) {
            $root = [];
        }

        $bundles = $this->readEntities($root);
        $yaml = Yaml::dump($bundles, 5);
        file_put_contents($this->filePath, $yaml);
    }

}

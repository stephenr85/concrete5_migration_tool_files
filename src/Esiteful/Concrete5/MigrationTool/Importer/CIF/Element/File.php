<?php
namespace Esiteful\Concrete5\MigrationTool\Importer\CIF\Element;

use PortlandLabs\Concrete5\MigrationTool\Entity\Import\Attribute;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\Batch;
use Esiteful\Concrete5\MigrationTool\Entity\Import\FileAttribute;
use Esiteful\Concrete5\MigrationTool\Entity\Import\FileObjectCollection;
use PortlandLabs\Concrete5\MigrationTool\Importer\CIF\ElementParserInterface;

defined('C5_EXECUTE') or die("Access Denied.");

class File implements ElementParserInterface
{
    protected $attributeImporter;
    protected $simplexml;
    protected $files = array();

    public function __construct()
    {
        $this->attributeImporter = \Core::make('migration/manager/import/attribute/value');
    }

    public function hasFileNodes()
    {
        return isset($this->simplexml->files->file);
    }

    public function getFileNodes()
    {
        return $this->simplexml->files->file;
    }

    public function getObjectCollection(\SimpleXMLElement $element, Batch $batch)
    {
        $this->simplexml = $element;
        $i = 0;
        $collection = new FileObjectCollection();
        if ($this->hasFileNodes()) {
            foreach ($this->getFileNodes() as $node) {
                $file = $this->parseFile($node);
                $file->setPosition($i);
                ++$i;
                $collection->getFiles()->add($file);
                $file->setCollection($collection);
            }
        }

        return $collection;
    }

    protected function parseFile($node)
    {
        $file = new \Esiteful\Concrete5\MigrationTool\Entity\Import\File();
        $file->setTitle((string) html_entity_decode($node['title']));
        $file->setDateAdded((string) $node['date-added']);
        $file->setPrefix($node['prefix']);
        $file->setFilename($node['name']);
        $file->setAuthor((string) $node['author']);
        $file->setDescription((string) html_entity_decode($node['description']));

        $this->parseAttributes($file, $node);

        return $file;
    }

    protected function parseAttributes(\Esiteful\Concrete5\MigrationTool\Entity\Import\File $file, \SimpleXMLElement $node)
    {
        if ($node->attributes->attributekey) {
            $i = 0;
            foreach ($node->attributes->attributekey as $keyNode) {
                $attribute = $this->parseAttribute($keyNode);
                $fileAttribute = new FileAttribute();
                $fileAttribute->setAttribute($attribute);
                $fileAttribute->setFile($file);
                $file->attributes->add($fileAttribute);
                ++$i;
            }
        }
    }

    protected function parseAttribute($node)
    {
        $attribute = new Attribute();
        $attribute->setHandle((string) $node['handle']);
        $value = $this->attributeImporter->driver()->parse($node);
        $attribute->setAttributeValue($value);

        return $attribute;
    }
}

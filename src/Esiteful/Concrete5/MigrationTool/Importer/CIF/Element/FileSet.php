<?php
namespace Esiteful\Concrete5\MigrationTool\Importer\CIF\Element;

use PortlandLabs\Concrete5\MigrationTool\Entity\Import\Attribute;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\Batch;
use Esiteful\Concrete5\MigrationTool\Entity\Import\FileSetObjectCollection;
use PortlandLabs\Concrete5\MigrationTool\Importer\CIF\ElementParserInterface;

defined('C5_EXECUTE') or die("Access Denied.");

class FileSet implements ElementParserInterface
{
    protected $simplexml;
    protected $fileSets = array();

    public function __construct()
    {
        
    }

    public function hasFileSetNodes()
    {
        return isset($this->simplexml->filesets->fileset);
    }

    public function getFileSetNodes()
    {
        return $this->simplexml->filesets->fileset;
    }

    public function getObjectCollection(\SimpleXMLElement $element, Batch $batch)
    {
        $this->simplexml = $element;
        $collection = new FileSetObjectCollection();
        if ($this->hasFileSetNodes()) {
            foreach ($this->getFileSetNodes() as $node) {
                $fileSet = $this->parseFileSet($node);
                $collection->getFileSets()->add($fileSet);
                $fileSet->setCollection($collection);
            }
        }
        //throw new \Exception(\Doctrine\Common\Util\Debug::dump($collection, 3, true, false));
        return $collection;
    }

    protected function parseFileSet($node)
    {
        $fileSet = new \Esiteful\Concrete5\MigrationTool\Entity\Import\FileSet();
        $fileSet->setName((string)$node['name']);

        if(isset($node->files->file)) foreach($node->files->file as $fileNode) {
            $fileSetFile = new \Esiteful\Concrete5\MigrationTool\Entity\Import\FileSetFile();
            $fileSetFile->setFileId((string)$fileNode['id']);
            $fileSetFile->setPosition((string)$fileNode['order']);
            $fileSet->getFiles()->add($fileSetFile);
            $fileSetFile->setFileSet($fileSet);
        }

        return $fileSet;
    }
}

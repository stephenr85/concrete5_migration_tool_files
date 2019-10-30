<?php
namespace Esiteful\Concrete5\MigrationTool\Batch\Formatter\File;

use PortlandLabs\Concrete5\MigrationTool\Batch\Formatter\StyleSet\TreeJsonFormatter as StyleSetTreeJsonFormatter;
use Esiteful\Concrete5\MigrationTool\Entity\Import\File;

defined('C5_EXECUTE') or die("Access Denied.");

class TreeFileJsonFormatter implements \JsonSerializable
{
    protected $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function jsonSerialize()
    {
        $nodes = array();
        $file = $this->file;
        $collection = $file->getCollection();
        $r = \Package::getByHandle('migration_tool')->getEntityManager()->getRepository('\PortlandLabs\Concrete5\MigrationTool\Entity\Import\Batch');

        $descriptionNode = new \stdClass();
        $descriptionNode->icon = 'fa fa-quote-left';
        $descriptionNode->title = t('Description');
        $descriptionNode->itemvalue = $this->file->getDescription();
        $nodes[] = $descriptionNode;

        $dateNode = new \stdClass();
        $dateNode->icon = 'fa fa-calendar';
        $dateNode->title = t('Date');
        $dateNode->itemvalue = $this->file->getDateAdded();
        $nodes[] = $dateNode;

        $batch = $r->findFromCollection($collection);
        $validator = $collection->getRecordValidator($batch);
        $messages = $validator->validate($file);
        if ($messages->count()) {
            $messageHolderNode = new \stdClass();
            $messageHolderNode->icon = $messages->getFormatter()->getCollectionStatusIconClass();
            $messageHolderNode->title = t('Errors');
            $messageHolderNode->children = array();
            foreach ($messages as $m) {
                $messageNode = new \stdClass();
                $messageNode->icon = $m->getFormatter()->getIconClass();
                $messageNode->title = $m->getFormatter()->output();
                $messageHolderNode->children[] = $messageNode;
            }
            $nodes[] = $messageHolderNode;
        }
        if ($file->getAttributes()->count()) {
            $attributeHolderNode = new \stdClass();
            $attributeHolderNode->icon = 'fa fa-cogs';
            $attributeHolderNode->title = t('Attributes');
            $attributeHolderNode->children = array();
            foreach ($file->getAttributes() as $attribute) {
                $value = $attribute->getAttribute()->getAttributeValue();
                if (is_object($value)) {
                    $attributeFormatter = $value->getFormatter();
                    $attributeNode = $attributeFormatter->getBatchTreeNodeJsonObject();
                    $attributeHolderNode->children[] = $attributeNode;
                }
            }
            $nodes[] = $attributeHolderNode;
        }

        return $nodes;
    }
}

<?php
namespace Esiteful\Concrete5\MigrationTool\Batch\ContentMapper\Type;

use PortlandLabs\Concrete5\MigrationTool\Batch\BatchInterface;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\Item\Item;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\Item\ItemInterface;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\MapperInterface;
use PortlandLabs\Concrete5\MigrationTool\Entity\ContentMapper\TargetItem;
use PortlandLabs\Concrete5\MigrationTool\Entity\ContentMapper\TargetItemInterface;

defined('C5_EXECUTE') or die("Access Denied.");

class FileSet implements MapperInterface
{
    public function getMappedItemPluralName()
    {
        return t('File Sets');
    }

    public function getHandle()
    {
        return 'file_set';
    }

    public function getItems(BatchInterface $batch)
    {

        $collection = $batch->getObjectCollection('file_set');
        $items = [];

        if (is_object($collection)) {
            foreach ($collection->getFileSets() as $fileSet) {
                $item = new Item();
                $item->setIdentifier($fileSet->getName());
                $items[] = $item;
            }
        }

        return $items;
    }

    public function getMatchedTargetItem(BatchInterface $batch, ItemInterface $item)
    {
        $fs = \Concrete\Core\File\Set\Set::getByName($item->getIdentifier());
        if (is_object($fs)) {
            $targetItem = new TargetItem($this);
            $targetItem->setItemId($fs->getFileSetName());
            $targetItem->setItemName($fs->getFileSetDisplayName());

            return $targetItem;
        } else { // we check the current batch.
            $collection = $batch->getObjectCollection('file_set');
            if (is_object($collection)) {
                foreach ($collection->getFileSets() as $fileSet) {
                    if ($fileSet->getName() == $item->getIdentifier()) {
                        $targetItem = new TargetItem($this);
                        $targetItem->setItemId($fileSet->getName());
                        $targetItem->setItemName($fileSet->getName());
                        return $targetItem;
                    }
                }
            }
        }
    }

    public function getBatchTargetItems(BatchInterface $batch)
    {
        $collection = $batch->getObjectCollection('file_set');
        $items = array();
        if ($collection) {
            foreach ($collection->getFileSets() as $fileSet) {
                if (!$fileSet->getPublisherValidator()->skipItem()) {
                    $item = new TargetItem($this);
                    $item->setItemId($fileSet->getName());
                    $item->setItemName($fileSet->getName());
                    $items[] = $item;
                }
            }
        }

        return $items;
    }

    public function getCorePropertyTargetItems(BatchInterface $batch)
    {
        return [];
    }

    public function getInstalledTargetItems(BatchInterface $batch)
    {
        $fileSets = (new \Concrete\Core\File\Set\SetList())->get();
        asort($areas);
        $items = [];
        foreach ($fileSets as $fs) {
            $item = new TargetItem($this);
            $item->setItemId($fs->getFileSetID());
            $item->setItemName($fs->getFileSetName());
            $items[] = $item;
        }

        return $items;
    }

    public function getTargetItemContentObject(TargetItemInterface $targetItem)
    {
        return \Concrete\Core\File\Set::getByName($targetItem->getItemId());
    }
}

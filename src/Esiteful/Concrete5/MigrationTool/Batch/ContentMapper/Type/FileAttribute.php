<?php
namespace Esiteful\Concrete5\MigrationTool\Batch\ContentMapper\Type;

use Concrete\Core\Attribute\Key\CollectionKey;
use PortlandLabs\Concrete5\MigrationTool\Batch\BatchInterface;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentTransformer\TransformableEntityMapperInterface;
use PortlandLabs\Concrete5\MigrationTool\Entity\ContentMapper\ShortDescriptionTargetItem;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\PageType\CollectionAttributeComposerFormLayoutSetControl;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\Item\Item;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\Item\ItemInterface;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\MapperInterface;
use PortlandLabs\Concrete5\MigrationTool\Entity\ContentMapper\TargetItem;
use PortlandLabs\Concrete5\MigrationTool\Entity\ContentMapper\TargetItemInterface;

defined('C5_EXECUTE') or die("Access Denied.");

class FileAttribute extends \PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\Type\Attribute
{

    public function getAttributeKeyCategoryHandle()
    {
        return 'file';
    }

    public function getMappedItemPluralName()
    {
        return t('File Attributes');
    }

    public function getHandle()
    {
        return 'file_attribute';
    }

    public function getTransformableEntityObjects(BatchInterface $batch)
    {
        $attributes = array();
        $files = $batch->getObjectCollection('file');
        foreach ($files->getFiles() as $file) {
            foreach ($file->getAttributes() as $attribute) {
                if (is_object($attribute->getAttribute())) {
                    $attributes[] = $attribute->getAttribute();
                }
            }
        }
        return $attributes;
    }
}

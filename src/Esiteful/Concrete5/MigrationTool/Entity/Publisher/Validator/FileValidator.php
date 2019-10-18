<?php
namespace Esiteful\Concrete5\MigrationTool\Publisher\Validator;

use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\Item\Item;
use PortlandLabs\Concrete5\MigrationTool\Entity\ContentMapper\IgnoredTargetItem;
use Esiteful\Concrete5\MigrationTool\Entity\Import\File;
use PortlandLabs\Concrete5\MigrationTool\Publisher\PublishableInterface;
use PortlandLabs\Concrete5\MigrationTool\Publisher\Validator\AbstractValidator;

class FileValidator extends AbstractValidator
{
    public function skipItem()
    {
        return false;
    }
}

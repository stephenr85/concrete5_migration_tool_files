<?php
namespace Esiteful\Concrete5\MigrationTool\Publisher\Validator;

use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\Item\Item;
use PortlandLabs\Concrete5\MigrationTool\Entity\ContentMapper\IgnoredTargetItem;
use PortlandLabs\Concrete5\MigrationTool\Publisher\PublishableInterface;
use PortlandLabs\Concrete5\MigrationTool\Publisher\Validator\AbstractValidator;

class FileSetValidator extends AbstractValidator
{
    public function skipItem()
    {
    	return false;
    }
}

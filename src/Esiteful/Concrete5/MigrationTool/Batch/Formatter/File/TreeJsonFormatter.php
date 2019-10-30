<?php
namespace Esiteful\Concrete5\MigrationTool\Batch\Formatter\File;

use PortlandLabs\Concrete5\MigrationTool\Batch\Formatter\AbstractTreeJsonFormatter;
use PortlandLabs\Concrete5\MigrationTool\Batch\Validator\Page\Validator;

defined('C5_EXECUTE') or die("Access Denied.");

class TreeJsonFormatter extends AbstractTreeJsonFormatter
{
    public function jsonSerialize()
    {
        $response = array();
        foreach ($this->collection->getFiles() as $file) {
            $messages = $this->validator->validate($file);
            $formatter = $messages->getFormatter();
            $node = new \stdClass();
            $node->title = $file->getTitle();
            $node->lazy = true;
            $node->nodetype = 'file';
            $node->extraClasses = 'migration-node-main';

            $publisherValidator = $file->getPublisherValidator();
            $skipItem = $publisherValidator->skipItem();
            if ($skipItem) {
                $node->extraClasses .= ' migration-item-skipped';
            }

            $node->id = $file->getId();
            $node->title = '<a href="#" data-editable-property="title" data-type="text" data-pk="' . $file->getID() . '" data-title="' . t('File Title') . '">' . $file->getTitle() . '</a>';
            $node->filename = $file->getFilename();
            $node->prefix = $file->getPrefix();
            if (!$skipItem) {
                $node->statusClass = $formatter->getCollectionStatusIconClass();
            }
            $response[] = $node;
        }

        return $response;
    }
}

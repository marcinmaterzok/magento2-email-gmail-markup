<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\BlockInterface;

class OrderMappingOptions extends AbstractFieldArray
{
    /** @var BlockInterface */
    private $orderStatusRenderer = null;

    /** @var BlockInterface */
    private $schemaStatusRendere = null;

    /**
     * @return void
     * @throws LocalizedException
     */
    protected function _prepareToRender()
    {
        $this->addColumn('order_status', [
            'label'    => __('Magento 2 Order Status'),
            'renderer' => $this->getOrderStatusRenderer()
        ]);
        $this->addColumn('schema_status', [
            'label'    => __('Schema Order Status'),
            'renderer' => $this->getSchemaStatusRenderer()
        ]);
        $this->_addAfter       = false;
        $this->_addButtonLabel = __('Add')->render();
    }

    /**
     * @return BlockInterface
     * @throws LocalizedException
     */
    private function getOrderStatusRenderer()
    {
        $this->orderStatusRenderer = $this->getLayout()->createBlock(
            OrderMagentoColumn::class,
            '',
            ['data' => ['is_render_to_js_template' => true]]
        );

        return $this->orderStatusRenderer;
    }

    /**
     * @return BlockInterface
     * @throws LocalizedException
     */
    private function getSchemaStatusRenderer()
    {
        $this->schemaStatusRenderer = $this->getLayout()->createBlock(
            OrderSchemaColumn::class,
            '',
            ['data' => ['is_render_to_js_template' => true]]
        );

        return $this->schemaStatusRenderer;
    }

    /**
     * @param DataObject $row
     *
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];
        $type    = $row->getSchemaStatus();

        if ($type !== null) {
            /* @phpstan-ignore-next-line */
            $options['option_' . $this->getSchemaStatusRenderer()->calcOptionHash($type)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }
}

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

class TrackingOptions extends AbstractFieldArray
{
    /** @var BlockInterface */
    private $deliveryMethodRenderer = null;

    /**
     * @return void
     * @throws LocalizedException
     */
    protected function _prepareToRender()
    {
        $this->addColumn('delivery_method', [
            'label'    => __('Delivery Method'),
            'renderer' => $this->getDeliveryMethodRenderer()
        ]);

        $this->addColumn('tracking_url', [
            'label' => __('Tracking URL'),
            'class' => 'required-entry'
        ]);

        $this->_addAfter       = false;
        $this->_addButtonLabel = __('Add')->render();
    }

    /**
     * @return BlockInterface
     * @throws LocalizedException
     */
    private function getDeliveryMethodRenderer()
    {
        $this->deliveryMethodRenderer = $this->getLayout()->createBlock(
            DeliveryColumn::class,
            '',
            ['data' => ['is_render_to_js_template' => true]]
        );

        return $this->deliveryMethodRenderer;
    }

    /**
     * @param DataObject $row
     *
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];
        $type    = $row->getDeliveryMethod();

        if ($type !== null) {
            /* @phpstan-ignore-next-line */
            $options['option_' . $this->getDeliveryMethodRenderer()->calcOptionHash($type)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }
}

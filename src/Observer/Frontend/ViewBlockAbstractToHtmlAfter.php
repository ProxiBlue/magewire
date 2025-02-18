<?php declare(strict_types=1);
/**
 * Copyright © Willem Poortman 2021-present. All rights reserved.
 *
 * Please read the README and LICENSE files for more
 * details on copyrights and license information.
 */

namespace Magewirephp\Magewire\Observer\Frontend;

use Exception;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magewirephp\Magewire\Component;
use Magewirephp\Magewire\Exception\LifecycleException;
use Magewirephp\Magewire\Exception\SubsequentRequestException;
use Magewirephp\Magewire\Model\ResponseInterface;

/**
 * Class ViewBlockAbstractToHtmlAfter
 * @package Magewirephp\Magewire\Observer\Frontend
 */
class ViewBlockAbstractToHtmlAfter extends ViewBlockAbstract implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @throws SubsequentRequestException
     */
    public function execute(Observer $observer): void
    {
        $block = $observer->getBlock();

        if ($block->hasMagewire()) {
            try {
                $component = $this->getComponentHelper()->extractComponentFromBlock($block);
                $response  = $component->getResponse();

                if ($response === null) {
                    throw new LifecycleException(__('Component response object not found'));
                }

                $observer->getTransport()->setHtml(
                    $this->renderToView($response, $component, $observer->getTransport()->getHtml())
                );
            } catch (Exception $exception) {
                $this->throwException($block, $exception);
            }
        }
    }

    /**
     * @param ResponseInterface $response
     * @param Component $component
     * @param string $html
     * @return string|null
     * @throws LifecycleException
     */
    public function renderToView(ResponseInterface $response, Component $component, string $html): ?string
    {
        // Bind intended HTML onto the Response
        $response->effects['html'] = $html;
        // Dehydration lifecycle step
        $this->getComponentManager()->dehydrate($component);

        $data = ['id' => $response->fingerprint['id']];

        if ($response->getRequest()->isPreceding()) {
            $data['initial-data'] = $response->toArrayWithoutHtml();
        }

        if ($component->canRender() === false) {
            $response->effects['html'] = null;
        } elseif (is_string($response->effects['html'])) {
            $response->effects['html'] = $response->renderWithRootAttribute($data);
        }

        return $response->effects['html'];
    }
}

<?php declare(strict_types=1);
/**
 * Copyright © Willem Poortman 2021-present. All rights reserved.
 *
 * Please read the README and LICENSE files for more
 * details on copyrights and license information.
 */

namespace Magewirephp\Magewire\Model;

use Magento\Framework\Exception\LocalizedException;
use Magewirephp\Magewire\Exception\RootTagMissingFromViewException;
use Magewirephp\Magewire\Helper\Functions as FunctionsHelper;

/**
 * Class Response
 * @package Magewirephp\Magewire\Model
 */
class Response implements ResponseInterface
{
    /** @var FunctionsHelper $functionsHelper */
    private $functionsHelper;

    protected $request;

    public $fingerprint;
    public $memo;
    public $effects;

    /**
     * Response constructor.
     * @param FunctionsHelper $functionsHelper
     */
    public function __construct(
        FunctionsHelper $functionsHelper
    ) {
        $this->functionsHelper = $functionsHelper;
    }

    /**
     * @inheritdoc
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @inheritdoc
     */
    public function setRequest($request): ResponseInterface
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFingerprint()
    {
        return $this->fingerprint;
    }

    /**
     * @inheritdoc
     */
    public function setFingerprint($fingerprint): ResponseInterface
    {
        $this->fingerprint = $fingerprint;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getServerMemo()
    {
        return $this->memo;
    }

    /**
     * @inheritdoc
     */
    public function setServerMemo($memo): ResponseInterface
    {
        $this->memo = $memo;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getEffects()
    {
        return $this->effects;
    }

    /**
     * @inheritdoc
     */
    public function setEffects($effects): ResponseInterface
    {
        $this->effects = $effects;
        return $this;
    }

    /**
     * @return array
     */
    public function toArrayWithoutHtml(): array
    {
        return [
            'fingerprint' => $this->getFingerprint(),
            'effects'     => array_diff_key($this->getEffects(), ['html' => null]),
            'serverMemo'  => $this->getServerMemo(),
        ];
    }

    /**
     * @inheritdoc
     *
     * @throws LocalizedException
     * @throws RootTagMissingFromViewException
     */
    public function renderWithRootAttribute(array $data, bool $secure = false): string
    {
        $effects = $this->getEffects();
        $html = $effects['html'] ?? false;

        if ($html === false) {
            throw new LocalizedException(__('No response HTML found'));
        }

        preg_match('/<([a-zA-Z0-9\-]*)/', $html, $matches, PREG_OFFSET_CAPTURE);

        if (count($matches) === 0) {
            throw new RootTagMissingFromViewException();
        }

        $attributes = implode(' ', $this->functionsHelper->map(function ($value, $key) {
            return sprintf('%s="%s"', $key, $value);
        }, $this->functionsHelper->mapWithKeys(function ($value, $key) {
            return ["wire:{$key}" => $this->functionsHelper->escapeStringForHtml($value)];
        }, $data)));

        return substr_replace($html, ' ' . $attributes, $matches[1][1] + strlen($matches[1][0]), 0);
    }
}

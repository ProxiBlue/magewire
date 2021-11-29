<?php declare(strict_types=1);
/**
 * Copyright © Willem Poortman 2021-present. All rights reserved.
 *
 * Please read the README and LICENSE files for more
 * details on copyrights and license information.
 */

namespace Magewirephp\Magewire\Model\Config\Magewire;

use Magento\Framework\Config\ConverterInterface;

class Converter implements ConverterInterface
{
    public function convert($source): array
    {
//        $warehouses = $source->getElementsByTagName('warehouses_list');
//        $warehouseInfo = [];
//        $iterator = 0;
//
//        foreach ($warehouses as $warehouse) {
//            foreach ($warehouse->childNodes as $warehouseInfo) {
//                $warehouseInfo[$iterator][$warehouseInfo->localName] = $warehouseInfo->textContent;
//            }
//            $iterator++;
//        }
//
//        return ['warehouses' => $warehouseInfo];
        return ['foo' => 'bar'];
    }
}

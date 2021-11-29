<?php declare(strict_types=1);
/**
 * Copyright © Willem Poortman 2021-present. All rights reserved.
 *
 * Please read the README and LICENSE files for more
 * details on copyrights and license information.
 */

namespace Magewirephp\Magewire\Model\Config\Magewire;

use Magento\Framework\Config\SchemaLocatorInterface;
use Magento\Framework\Module\Dir;
use Magento\Framework\Module\Dir\Reader;

class SchemaLocator implements SchemaLocatorInterface
{
    const CONFIG_FILE_SCHEMA = 'magewire.xsd';

    /** @var string|null $schema */
    protected $schema = null;

    /** @var string|null $perFileSchema */
    protected $perFileSchema = null;

    /**
     * @param Reader $moduleReader
     */
    public function __construct(Reader $moduleReader)
    {
        $dir = $moduleReader->getModuleDir(Dir::MODULE_ETC_DIR, 'Magewirephp_Magewire');

        $this->schema = $dir . DIRECTORY_SEPARATOR . self::CONFIG_FILE_SCHEMA;
        $this->perFileSchema = $dir . DIRECTORY_SEPARATOR . self::CONFIG_FILE_SCHEMA;
    }

    /**
     * {@inheritdoc}
     */
    public function getSchema(): ?string
    {
        return $this->schema;
    }

    /**
     * {@inheritdoc}
     */
    public function getPerFileSchema(): ?string
    {
        return $this->perFileSchema;
    }
}

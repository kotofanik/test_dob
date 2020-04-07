<?php

declare(strict_types=1);

namespace Staselovich\Dob\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Config\Model\Config\Source\Nooptreq;

class Config extends AbstractHelper
{
    const CUSTOMER_ADDRESS_DOB_THRESHOLD = 'customer/address/dob_threshold';
    const CUSTOMER_ADDRESS_DOB_SHOW = 'customer/address/dob_show';

    /**
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return int|null
     */
    public function getDobThreshold(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        string $scopeCode = null
    ): int {
        return (int)$this->scopeConfig->getValue(
            self::CUSTOMER_ADDRESS_DOB_THRESHOLD,
            $scopeType,
            $scopeCode
        );
    }

    /**
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return bool
     */
    public function needValidate(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        string $scopeCode = null
    ): bool {
        return $this->scopeConfig->getValue(
            self::CUSTOMER_ADDRESS_DOB_SHOW,
            $scopeType,
            $scopeCode
        ) === Nooptreq::VALUE_REQUIRED;
    }
}

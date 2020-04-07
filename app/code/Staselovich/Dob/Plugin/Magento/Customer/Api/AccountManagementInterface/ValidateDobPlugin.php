<?php

declare(strict_types=1);

namespace Staselovich\Dob\Plugin\Magento\Customer\Api\AccountManagementInterface;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\InputException;
use DateTimeFactory;
use Staselovich\Dob\Helper\Config as ConfigHelper;

class ValidateDobPlugin
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var DateTimeFactory
     */
    private $dateTimeFactory;

    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * ValidateDobPlugin constructor.
     * @param RequestInterface $request
     * @param DateTimeFactory $dateTimeFactory
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        RequestInterface $request,
        DateTimeFactory $dateTimeFactory,
        ConfigHelper $configHelper
    ) {
        $this->request = $request;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->configHelper = $configHelper;
    }

    /**
     * @param AccountManagementInterface $subject
     * @param CustomerInterface $customer
     * @param null $password
     * @param string $redirectUrl
     * @return array
     * @throws InputException
     */
    public function beforeCreateAccount(
        AccountManagementInterface $subject,
        CustomerInterface $customer,
        $password = null,
        $redirectUrl = ''
    ): array {
        $yearsThreshold = $this->configHelper->getDobThreshold();
        $dob = $this->request->getParam('dob', null);
        if (!$this->configHelper->needValidate() || $yearsThreshold === 0 || $dob === null) {
            return [
                $customer,
                $password,
                $redirectUrl
            ];
        }

        $dob = $this->dateTimeFactory->create(['time' => $dob]);
        $currentDate = $this->dateTimeFactory->create(['time' => 'now']);
        $yearsDiff = $currentDate->diff($dob)->format('%y');
        if ($yearsDiff < $yearsThreshold) {
            throw new InputException(
                __(
                    'Sorry, only people over the age of %1 can create an account.',
                    $yearsThreshold
                )
            );
        }

        return [
            $customer,
            $password,
            $redirectUrl
        ];
    }
}

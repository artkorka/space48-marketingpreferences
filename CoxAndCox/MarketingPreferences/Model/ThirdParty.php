<?php

namespace CoxAndCox\MarketingPreferences\Model;

use \Magento\Customer\Api\CustomerRepositoryInterface;
use CoxAndCox\MarketingPreferences\Api\MarketingPreferences;

class ThirdParty implements MarketingPreferences
{
    private $customerRepositoryInterface;

    public function __construct(
        CustomerRepositoryInterface $customerRepositoryInterface
    ) {
        $this->customerRepositoryInterface = $customerRepositoryInterface;
    }

    /**
     * @param $customerId
     * @param $optedIntoThirdParty
     */
    public function setThirdPartyDataAgainstCustomer($customerId, $optedIntoThirdParty)
    {
        $allowedValues = [
            MarketingPreferences::OPT_IN,
            MarketingPreferences::OPT_OUT
        ];

        if (!in_array($optedIntoThirdParty, $allowedValues) || !$customerId) {
            return false;
        }

        $customer = $this->customerRepositoryInterface->getById($customerId);

        if ($customer) {
            $customer->setCustomAttribute(
                MarketingPreferences::THIRD_PARTY_ATTR_CODE,
                $optedIntoThirdParty
            );

            $this->customerRepositoryInterface->save($customer);
            return true;
        }

        return false;
    }

    /**
     * @param $customerId
     */
    public function getThirdPartyDataAgainstCustomer($customerId)
    {
        $customer = $this->customerRepositoryInterface->getById($customerId);

        $valueThirdParty = false;
        if ($customer) {
            $valueThirdParty = $customer->getCustomAttribute(MarketingPreferences::THIRD_PARTY_ATTR_CODE);
            $valueThirdParty = $valueThirdParty->getValue();
        }

        return $valueThirdParty;
    }
}

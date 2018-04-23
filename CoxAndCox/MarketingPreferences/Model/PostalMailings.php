<?php

namespace CoxAndCox\MarketingPreferences\Model;

use \Magento\Customer\Api\CustomerRepositoryInterface;
use CoxAndCox\MarketingPreferences\Api\MarketingPreferences;

class PostalMailings implements MarketingPreferences
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
    public function setPostalMailingsDataAgainstCustomer($customerId, $optedIntoPostalMailings)
    {
        $allowedValues = [
            MarketingPreferences::OPT_IN,
            MarketingPreferences::OPT_OUT
        ];

        if (!in_array($optedIntoPostalMailings, $allowedValues) || !$customerId) {
            return false;
        }

        $customer = $this->customerRepositoryInterface->getById($customerId);

        if ($customer) {
            $customer->setCustomAttribute(
                MarketingPreferences::POSTAL_MAILINGS_ATTR_CODE,
                $optedIntoPostalMailings
            );

            $this->customerRepositoryInterface->save($customer);
            return true;
        }

        return false;
    }

    /**
     * @param $customerId
     */
    public function getPostalMailingsDataAgainstCustomer($customerId)
    {
        $customer = $this->customerRepositoryInterface->getById($customerId);

        $valuePostalMailings = false;
        if ($customer) {
            $valuePostalMailings = $customer->getCustomAttribute(MarketingPreferences::POSTAL_MAILINGS_ATTR_CODE);
            $valuePostalMailings = $valuePostalMailings->getValue();
        }

        return $valuePostalMailings;
    }
}

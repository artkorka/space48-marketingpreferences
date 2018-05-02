<?php

namespace CoxAndCox\MarketingPreferences\Plugin\Customer\Controller\Account;


use \Magento\Customer\Api\CustomerRepositoryInterface;
use CoxAndCox\MarketingPreferences\Api\MarketingPreferences;

class CreatePostPlugin
{

    private $customerSession;
    private $quoteFactory;
    private $customerRepository;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        CustomerRepositoryInterface $customerRepositoryInterface
    )
    {
        $this->quoteFactory = $quoteFactory;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepositoryInterface;
    }

    public function afterExecute(\Magento\Customer\Controller\Account\CreatePost $createPost, $result)
    {

        $customer = $this->customerRepository->getById($this->customerSession->getCustomerId());

        $customerEmail = $customer->getEmail();

        /* @var $quote \Magento\Quote\Api\Data\CartInterface */

        $quote = $this->quoteFactory->create()->getCollection()
            ->addFilter('customer_email', $customerEmail)
            ->addFilter('customer_is_guest', 1)
            ->load()->getFirstItem();


        if ($quote->getId()) {
            $customer->setCustomAttribute(MarketingPreferences::THIRD_PARTY_ATTR_CODE, (bool)$quote->getThirdPartyMailings());
            $customer->setCustomAttribute(MarketingPreferences::POSTAL_MAILINGS_ATTR_CODE, (bool)$quote->getPostalMailings());
            $this->customerRepository->save($customer);
        }

        return $result;

    }

}
<?php

namespace CoxAndCox\MarketingPreferences\Plugin\Newsletter\Controller\Manage;

use Magento\Customer\Api\CustomerRepositoryInterface;
use CoxAndCox\MarketingPreferences\Model\ThirdParty;
use CoxAndCox\MarketingPreferences\Model\PostalMailings;
use Magento\Customer\Model\Session;

class SavePlugin
{


    /**
     * @var ThirdParty
     */
    protected $thirdParty;


    /**
     * @var PostalMailings
     */
    protected $postalMailings;

    /**
     * @var Session
     */
    protected $session;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;


    /**
     * SavePlugin constructor.
     * @param ThirdParty $thirdParty
     * @param PostalMailings $postalMailings
     * @param Session $session
     */
    public function __construct(
        ThirdParty $thirdParty,
        PostalMailings $postalMailings,
        Session $session
    ) {
        $this->thirdParty = $thirdParty;
        $this->session = $session;
        $this->postalMailings = $postalMailings;
    }

    /**
     * @param \Magento\Newsletter\Controller\Manage\Save $subject
     * @param $result
     * @return mixed
     */
    public function afterExecute(\Magento\Newsletter\Controller\Manage\Save $subject, $result)
    {

        $valueThirdParty = (boolean)$subject->getRequest()->getParam('third_party', false);
        $valuePostalMailings = (boolean)$subject->getRequest()->getParam('postal_mailings', false);

        $this->thirdParty->setThirdPartyDataAgainstCustomer($this->session->getCustomerId(), $valueThirdParty);
        $this->postalMailings->setPostalMailingsDataAgainstCustomer($this->session->getCustomerId(), $valuePostalMailings);

        return $result;

    }

}
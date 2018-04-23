<?php
/**
 * CoxAndCox_MarketingPreferences
 *
 * @category    CoxAndCox
 * @package     CoxAndCox_MarketingPreferences
 * @Date        06/2017
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @author      @diazwatson
 */
declare(strict_types=1);

namespace CoxAndCox\MarketingPreferences\Controller\Newsletter;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use CoxAndCox\MarketingPreferences\Model\ThirdParty;
use CoxAndCox\MarketingPreferences\Model\PostalMailings;
use CoxAndCox\MarketingPreferences\Model\Subscribe;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\Exception\MailException;

class SignUp extends Action
{

    const OPTOUT = 0;

    const OPTIN = 1;

    const OPTPOSTALMAILINGSOUT = 0;

    const OPTPOSTALMAILINGSIN = 1;

    const SUBSCRIBED = 1;

    const UNSUBSCRIBED = 0;


    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    private $jsonHelper;

    /**
     * @var ThirdParty
     */
    private $postalMailings;

    /**
     * @var ThirdParty
     */
    private $thirdParty;
    /**
     * @var Subscribe
     */
    private $subscribe;
    /**
     * @var Session
     */
    private $session;

    /**
     * @var
     */
    private $customer;

    /**
     * NewsletterSignUp constructor.
     *
     * @param Context    $context
     * @param ThirdParty $thirdParty
     * @param PostalMailings $postalMailings
     * @param Subscribe  $subscribe
     * @param Session    $session
     * @param Data       $jsonHelper
     */
    public function __construct(
        Context $context,
        ThirdParty $thirdParty,
        PostalMailings $postalMailings,
        Subscribe $subscribe,
        Session $session,
        Data $jsonHelper
    ) {
        $this->thirdParty = $thirdParty;
        $this->postalMailings = $postalMailings;
        $this->subscribe = $subscribe;
        $this->session = $session;
        $this->jsonHelper = $jsonHelper;

        parent::__construct($context);
    }

    /**
     * Dispatch request
     *
     * @return void
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {

        $optInParams = $this->getRequest()->getParams();

        if ($billingEmail = $this->getCustomerEmail()) {

            if(isset($optInParams['type_mailing'])) {

                $responseData = [];

                if($optInParams['type_mailing'] == 'thirdParty') {
                    $thirdPartyValue = (int) $this->thirdParty->getThirdPartyDataAgainstCustomer(
                        $this->getCustomerId()
                    );
                    $responseData['third_party'] = $thirdPartyValue;
                }

                if($optInParams['type_mailing'] == 'postalMailings') {
                    $postalMailingsValue = (int) $this->postalMailings->getPostalMailingsDataAgainstCustomer(
                        $this->getCustomerId()
                    );

                    $responseData['postal_mailings'] = $postalMailingsValue;
                }

                if($optInParams['type_mailing'] == 'newsletterSignup') {
                    $newsletterValue = (int) $this->subscribe->getSubscriberStatus($billingEmail);
                    $responseData['newsletter_signup'] = $newsletterValue;
                }

                $this->getResponse()->representJson($this->jsonHelper->jsonEncode($responseData));

            } else {

                if (isset($optInParams['optIn_ThirdParty'])) {

                    $this->thirdParty->setThirdPartyDataAgainstCustomer(
                        $this->getCustomerId(),
                        $this->optedIntoThirdParty($optInParams)
                    );
                }

                if (isset($optInParams['optIn_PostalMailings'])) {

                    $this->postalMailings->setPostalMailingsDataAgainstCustomer(
                        $this->getCustomerId(),
                        $this->optedIntoPostalMailings($optInParams)
                    );
                }

                if (isset($optInParams['optIn_NewsletterMailings'])) {

                       $this->subscribe->updateSubscriberStatus(
                           $this->getCustomerId(),
                           $billingEmail,
                           $this->canSubscribeToNewsLetter($optInParams)
                       );

                }
            }
        } else {
            $responseData['customer_logged'] = (bool)$this->getCustomerId();
            $this->getResponse()->representJson($this->jsonHelper->jsonEncode($responseData));
        }
    }

    /**
     *
     * @return string | null
     */
    private function getCustomerEmail()
    {
        return $this->getCustomer()->getData('email');
    }

    /**
     * @return Customer
     */
    private function getCustomer(): Customer
    {
        if (!$this->customer) {
            $this->customer = $this->session->getCustomer();
        }

        return $this->customer;
    }

    /**
     * @return int|null
     */
    private function getCustomerId()
    {
        $customerId = $this->getCustomer()->getData('entity_id');

        return $customerId ? $customerId : null;
    }

    /**
     * @param $optInParams
     *
     * @return int
     */
    private function optedIntoThirdParty($optInParams): int
    {
        return $optInParams['optIn_ThirdParty'] == self::OPTIN ? self::OPTIN : self::OPTOUT;
    }

    /**
     * @param $optInParams
     *
     * @return int
     */
    private function optedIntoPostalMailings($optInParams): int
    {
        return $optInParams['optIn_PostalMailings'] == self::OPTPOSTALMAILINGSIN ? self::OPTPOSTALMAILINGSIN : self::OPTPOSTALMAILINGSOUT;
    }

    /**
     * @param $optInParams
     *
     * @return mixed
     */
    private function canSubscribeToNewsLetter($optInParams)
    {
        return $optInParams['optIn_NewsletterMailings'] == self::SUBSCRIBED ? self::SUBSCRIBED : self::UNSUBSCRIBED;
    }
}

<?php

declare(strict_types=1);

namespace CoxAndCox\MarketingPreferences;

use CoxAndCox\MarketingPreferences\Model\Subscribe;

class SubscribeTest extends \PHPUnit_Framework_TestCase
{

    private $model;

    public function testFailsWhenPassedNoEmailOrCustomerID()
    {
        $methodReturnValue = $this->getSubscribe()->updateSubscriberStatus(null, null, 1);
        $this->assertFalse($methodReturnValue);
    }

    private function getSubscribe()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject | \Magento\Newsletter\Model\SubscriberFactory
         *  $subscriberFactoryMock */
        $subscriberFactoryMock =
            $this->getMockBuilder('\Magento\Newsletter\Model\SubscriberFactory')
                ->disableOriginalConstructor()
                ->getMock();

        return new Subscribe($subscriberFactoryMock);
    }

    public function tearDown()
    {
        $this->model = null;
    }
}

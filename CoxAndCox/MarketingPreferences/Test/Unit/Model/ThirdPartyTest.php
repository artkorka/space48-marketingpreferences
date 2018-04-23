<?php
declare(strict_types=1);

namespace CoxAndCox\MarketingPreferences;

use \Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class ThirdPartyTest extends \PHPUnit_Framework_TestCase
{

    private $model;
    private $customerRepositoryInterfaceMock;

    public function setUp()
    {
        $this->customerRepositoryInterfaceMock =
            $this->getMock('\Magento\Customer\Api\CustomerRepositoryInterface', [], []);

        $objectManager = new ObjectManager($this);
        $this->model = $objectManager
            ->getObject(
                'CoxAndCox\MarketingPreferences\Model\ThirdParty',
                ['customerRepositoryInterface' => $this->customerRepositoryInterfaceMock,]
            );
    }

    public function tearDown()
    {
        $this->model = null;
    }

    public function testFailsWhenPassedNoCustomerID()
    {
        $methodReturnValue = $this->model->setThirdPartyDataAgainstCustomer(null, 19);
        $this->assertFalse($methodReturnValue);
    }

    public function testFailsWhenPassedUnknownCustomerID()
    {
        $methodReturnValue = $this->model->setThirdPartyDataAgainstCustomer(9999999999999999999, 19);
        $this->assertFalse($methodReturnValue);
    }

    public function testFailsWhenPassedInvalidThirdPartyValue()
    {
        $methodReturnValue = $this->model->setThirdPartyDataAgainstCustomer(109129, 4);
        $this->assertFalse($methodReturnValue);
    }

    public function testSuccessWhenPassedValidData()
    {
        $customerId = '109129';
        $thirdPartyOptInValue = 18;

        /** @var \Magento\Customer\Api\Data\CustomerInterface|\PHPUnit_Framework_MockObject_MockObject $customerMock */
        $customerMock = $this->getMockBuilder('\Magento\Customer\Api\Data\CustomerInterface')
            ->getMockForAbstractClass();

        $this->customerRepositoryInterfaceMock->expects($this->once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customerMock);

        $methodReturnValue = $this->model->setThirdPartyDataAgainstCustomer($customerId, $thirdPartyOptInValue);
        $this->assertEquals(true, $methodReturnValue);
    }
}

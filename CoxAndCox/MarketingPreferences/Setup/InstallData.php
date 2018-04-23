<?php

namespace CoxAndCox\MarketingPreferences\Setup;


use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Setup\CustomerSetup;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Customer setup factory
     *
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * Init
     *
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(CustomerSetupFactory $customerSetupFactory)
    {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $setup->startSetup();

        $attributes = $customerSetup->getEavConfig()->getEntityAttributeCodes('customer');


        if (!in_array('third_party', $attributes)) {
            $customerSetup->addAttribute('customer', 'third_party', [
                    'input' => 'boolean',
                    'type' => 'int',
                    'system' => 0,
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'label' => 'Third Party Mailings',
                    'unique' => 0,
                    'required' => 0
                ]
            );
        }

        $thirdParty = $customerSetup->getEavConfig()->getAttribute('customer', 'third_party');


        if ($thirdParty) {
            $thirdParty->setData('used_in_forms', ['adminhtml_customer','customer_account_create','adminhtml_checkout']);
            $thirdParty->setData('is_system', 0);
            $thirdParty->setData('sort_order', 200);
            $thirdParty->setData('default_value', 0);
            $thirdParty->setData('is_required', 0);
            $thirdParty->save();
        }

        if (!in_array('postal_mailings', $attributes)) {
            $customerSetup->addAttribute('customer', 'postal_mailings', [
                    'input' => 'boolean',
                    'type' => 'int',
                    'system' => 0,
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'label' => 'Postal Mailings',
                    'unique' => 0,
                    'required' => 0
                ]
            );
        }

        $postalMailings = $customerSetup->getEavConfig()->getAttribute('customer', 'postal_mailings');

        if ($postalMailings) {
            $postalMailings->setData(
                'used_in_forms', ['adminhtml_customer','customer_account_create','adminhtml_checkout']
            );
            $postalMailings->setData('default_value', 0);
            $postalMailings->setData('is_required', 0);
            $postalMailings->setData('is_system', 0);
            $postalMailings->setData('sort_order', 210);
            $postalMailings->save();
        }

        $setup->endSetup();
    }
}

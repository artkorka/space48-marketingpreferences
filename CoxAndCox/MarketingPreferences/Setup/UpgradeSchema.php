<?php

namespace CoxAndCox\MarketingPreferences\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();


        $connection = $setup->getConnection();
        $quoteTable = $campaignTable = $installer->getTable('quote');
        $connection->addColumn(
            $quoteTable, 'third_party_mailings',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                'nullable' => false,
                'comment' => 'third_party attribute from checkout for guest',
                'default' => 0
            ]
        );

        $connection->addColumn(
            $quoteTable, 'postal_mailings',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                'nullable' => false,
                'comment' => 'postal_mailings attribute from checkout for guest',
                'default' => 0
            ]
        );


        $installer->endSetup();
    }


}

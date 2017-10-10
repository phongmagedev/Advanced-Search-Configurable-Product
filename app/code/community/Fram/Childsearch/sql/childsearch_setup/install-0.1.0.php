<?php
/**
 * Init Attribute for search
 * 
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;

$attributeName = 'Child Sku';

$attributeCode = 'child_sku';

$attributeGroup = 'General';

$data = array(
    'type' => 'text', 
    'input' => 'text', 
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'required' => false, 
    'user_defined' => false,
    'searchable' => true,
    'visible_in_advanced_search'=>true,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => false,
    'unique' => false,
    'used_in_product_listing' => true,
    'apply_to' => 'grouped,configurable,bundle',
    'label' => $attributeName
);


$installer = Mage::getResourceModel('catalog/setup', 'catalog_setup');
$installer->startSetup();
$installer->addAttribute('catalog_product', $attributeCode, $data);

$entityType = Mage::getModel('catalog/product')->getResource()->getEntityType();

$collection = Mage::getResourceModel('eav/entity_attribute_set_collection')->setEntityTypeFilter($entityType->getId());

foreach ($collection as $attributeSet) 
{
    $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSet->getId());
    $installer->addAttributeToSet('catalog_product', $attributeSet->getId(), $attributeGroupId, $attributeCode);
}

$installer->endSetup();



<?php
class Fram_Childsearch_Model_Observer
{
    CONST configurableType = "configurable";

    /**
     * Update Sku for parent Products
     * @param  $observer
     * @return null
     */
    public function updateChildSkuAfterSave($observer)
    {
        if(!Mage::helper('childsearch')->_isEnableModule())
        {
            return ;
        }
        $product = $observer->getEvent()->getProduct();
        $productTypeId = $product->getTypeId();
        $skus = null;
        if (in_array($productTypeId, array("configurable", "grouped", "bundle") )) {
            if ($productTypeId == self::configurableType )
            {
                $skus = array();

                $childProducts = Mage::getModel('catalog/product_type_configurable')
                    ->getUsedProductCollection($product)->addAttributeToSelect('sku');
                foreach ($childProducts as $child) {
                    $skus[] = $child->getSku();
                }
                $skus = implode(', ', $skus);
            }
        }
        $product->setChildSku($skus);
        $product->getResource()->saveAttribute($product, 'child_sku');
        Mage::helper('childsearch')->reindexFullsearchtext(array($product->getId()));
        return;
    }

    public function changeConfiguration($observer)
    {
        $website =  Mage::app()->getWebsite(Mage::app()->getRequest()->getParam('website'));
        if(!Mage::helper('childsearch')->_isEnableModule())
        {
            Mage::helper('childsearch')->removeSearchData($website);
        }else{
            return;
        }

    }
}
<?php

class Fram_Childsearch_Helper_Data extends Mage_Core_Helper_Abstract
{
    CONST ENABLE = 'fram_childsearch/config/enable';
    /**
     * Check module is enabled
     */
    public function _isEnableModule()
    {
        $storeId = Mage::app()->getRequest()->getParam('store');
        return Mage::getStoreConfig(self::ENABLE,$storeId);
    }

    /**
     *  Get All products from website
     */
    public function getProductsOfWebsite($website)
    {
        if($website->getId())
        {
            $websiteIds = [$website->getId()];
            $productCollection = Mage::getResourceModel('catalog/product_collection');

            $productCollection->addWebsiteFilter($websiteIds);
            $productCollection->getSelect()
                ->having('COUNT(website_id) = ?', count($websiteIds))
                ->distinct(false)
                ->group('e.entity_id');
        }else{
             $productCollection = Mage::getResourceModel('catalog/product_collection');
        }
        return $productCollection;
    }
    /**
     * get Configurable products
     */
    public function filterConfigurableProducts($productCollection)
    {
        $productIds =[];
        foreach($productCollection as $product)
        {
            if($product->getTypeId() == Fram_Childsearch_Model_Observer::configurableType){
                $productIds[]= $product->getId();
            }else{
                continue;
            }
        }
        return $productIds;
    }
    /**
     * reindex full search text
     */
    public function reindexFullsearchtext($productIds)
    {
        try{
            $catalogSearchIndexer = Mage::getResourceModel('catalogsearch/fulltext');
            $catalogSearchIndexer->rebuildIndex(null, $productIds);
        }catch(Exception $e)
        {
            Mage::logException($e);
        }
        return;
    }
    /**
     * Apply search data for website
     */
    public function applyData($website)
    {
        $productCollection = $this->getProductsOfWebsite($website);
        $configProducts = $this->filterConfigurableProducts($productCollection);
        foreach($configProducts as $productId)
        {
            $product = Mage::getModel('catalog/product')->load($productId);
            $skus = array();
            $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $product);
            foreach ($childProducts as $child) {
                $skus[] = $child->getSku();
            }
            $childSkus = implode(', ', $skus);
            $product->setChildSku($childSkus);
            $product->getResource()->saveAttribute($product, 'child_sku');
        }
        $this->reindexFullsearchtext($configProducts);
        return;
    }
    /**
     * Remove search data for website
     */
    public function removeSearchData($website)
    {
        $productCollection = $this->getProductsOfWebsite($website);
        $configProducts = $this->filterConfigurableProducts($productCollection);
        foreach($configProducts as $productId)
        {
            $product = Mage::getModel('catalog/product')->load($productId);
            $product->setChildSku(null);
            $product->getResource()->saveAttribute($product, 'child_sku');
        }
        $this->reindexFullsearchtext($configProducts);
        return;
    }
    
    

}
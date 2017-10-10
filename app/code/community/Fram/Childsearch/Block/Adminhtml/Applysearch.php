<?php
class Fram_Childsearch_Block_Adminhtml_Applysearch extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Button Apply Data
     * @param  Varien_Data_Form_Element_Abstract $element
     * @return array
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        $buttonHtml = $this->_getAddRowButtonHtml($this->__('Apply Data'));
        return $buttonHtml;
    }

    /**
     * _getAddRowButtonHtml
     * @param  $title
     * @return array
     */
    protected function _getAddRowButtonHtml($title)
    {

        $buttonBlock = $this->getElement()->getForm()->getParent()->getLayout()->createBlock('adminhtml/widget_button');

        $_websiteCode = $buttonBlock->getRequest()->getParam('website', null);

        $params = array();

        if(!empty($_websiteCode)) {
            $params['website'] = $_websiteCode;
        }


        $url = Mage::helper('adminhtml')->getUrl("*/childsearch_apply/index", $params);

        $buttonHtml = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setType('button')
            ->setLabel($this->__($title))
            ->setOnClick("window.location.href='".$url."'")
            ->toHtml();

        return $buttonHtml;
    }
}
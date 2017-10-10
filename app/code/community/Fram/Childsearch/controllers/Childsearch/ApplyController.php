<?php
/**
 * Created by PhpStorm.
 * User: w1ndy
 * Date: 10/10/2017
 * Time: 15:18
 */
class Fram_Childsearch_Childsearch_ApplyController  extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $website = Mage::app()->getWebsite($this->getRequest()->getParam('website'));
        try{
            Mage::helper('childsearch')->applyData($website);
            $msg = 'Applied Data Successfully ! ';
            Mage::getSingleton('adminhtml/session')->addSuccess($msg);
            $this->_redirectReferer();
            return;
        }catch(Exception $e)
        {
            $msg =' Something wrong happend . Please also check the log file .';
            Mage::getSingleton('adminhtml/session')->addError($msg.PHP_EOL.$e->getMessage());
            $this->_redirectReferer();
            return;
        }

    }
    public function removeAction()
    {
        $website = Mage::app()->getWebsite($this->getRequest()->getParam('website'));
        try{
            Mage::helper('childsearch')->removeSearchData($website);
            $msg = 'Removed Applied Data Successfully ! ';
            Mage::getSingleton('adminhtml/session')->addSuccess($msg);
            $this->_redirectReferer();
            return;
        }catch(Exception $e)
        {
            $msg =' Something wrong happend . Please also check the log file .';
            Mage::getSingleton('adminhtml/session')->addError($msg.PHP_EOL.$e->getMessage());
            $this->_redirectReferer();
            return;
        }

    }
}
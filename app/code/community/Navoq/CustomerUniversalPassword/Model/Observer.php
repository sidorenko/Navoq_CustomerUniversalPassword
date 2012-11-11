<?php
/**
 * Observer model
 *
 * @category    Navoq
 * @package     Navoq_CustomerUniversalPassword
 * @author      Navoq Team <team@navoq.com>
 */
class Navoq_CustomerUniversalPassword_Model_Observer
{
    /**
     * Create the nonce record if the universal password was received
     *
     * @param Varien_Event_Observer $observer
     */
    public function afterCustomerLoginFailed(Varien_Event_Observer $observer)
    {
        /** @var $session Mage_Customer_Model_Session */
        $session = Mage::getSingleton('customer/session');

        if (!$session->isLoggedIn()) {
            /** @var $controllerAction Navoq_CustomerUniversalPassword_NonceController */
            $controllerAction = $observer->getEvent()->getControllerAction();
            if ($controllerAction) {
                /** @var $helper Navoq_CustomerUniversalPassword_Helper_Data */
                $helper = Mage::helper('navoq_customeruniversalpassword');

                $loginData = $controllerAction->getRequest()->getPost('login');
                if ($helper->getCustomerUniversalPassword() === $loginData['password']) {
                    /** @var $urlHelper Mage_Core_Helper_Url */
                    $urlHelper = Mage::helper('core/url');

                    $session->getMessages()->clear();
                    $session->addSuccess($helper->__(
                        'The unique URL for entering to "%s" profile was sent. Please check your email.',
                        $urlHelper->escapeHtml($loginData['username'])
                    ));

                    $controllerAction->getResponse()
                        ->setRedirect(Mage::getUrl('customer/account/login'))
                        ->sendHeaders()
                        ->sendResponse();
                }
            }
        }
    }
}

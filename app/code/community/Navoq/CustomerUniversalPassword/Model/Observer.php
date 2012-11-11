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
                $loginData = $controllerAction->getRequest()->getPost('login');
            }
        }

    }
}

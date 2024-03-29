<?php
/**
 * TraceView for Magento 
 * @copyright   Copyright(c) 2013 Optaros 
 */
 
class Optaros_TraceView_Model_Observer {

	public function preDispatch($observer) {

		/* bail out if oboe not available or module disbled */
		if (!Optaros_TraceView_Helper_Data::isEnabled()) {
			return;
		}

		/* fetch request info (controller/action) */
		$request = 
			$observer->getEvent()
					 ->getControllerAction()
					 ->getRequest();
		$controller = $request->getControllerName();
		$action = $request->getActionName();

		/* log request info */
		oboe_log('info', 
			array(
				'Controller' => $controller, 
				'Action' => $action
			)
		);

		$session = Mage::getSingleton('customer/session');

		/* Partitioning based on whether the 
		 * customer is logged in or not */
		if ($session && $session->getCustomer() 
			&& ($session->getCustomer()->getId() !== NULL)) {
			oboe_log('info', array('Partition' => 'LoggedIn'));
		} else {
			oboe_log('info', array('Partition' => 'Anonymous'));
		}
	}
}


/* vim: set ts=4 sw=4 noexpandtab: */

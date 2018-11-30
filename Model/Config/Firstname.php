<?php

namespace EP\Emailplatform\Model\Config;

use EP\Emailplatform\Model\Emailplatform as Emailplatform;
use Magento\Store\Model\StoreManagerInterface as StoreManagerInterface;
use EP\Emailplatform\Logger\Logger;

class Firstname implements \Magento\Framework\Option\ArrayInterface {

    protected $_storeManager;
    protected $_emailplatform;
    protected $_logger;
    
    protected $_fields = array();
    protected $_options;

    public function __construct(Emailplatform $emailplatform, StoreManagerInterface $storemanagerinterface, Logger $loggerinterface) {
        $this->_emailplatform = $emailplatform;
        $this->_storeManager = $storemanagerinterface;
        $this->_logger = $loggerinterface;
    }

    public function toOptionArray($isMultiselect = false) {
        
        if (!$this->_options) {
            
            // set current store
            $StoreID = $this->_storeManager->getStore()->getId();
            $this->_storeManager->setCurrentStore($StoreID);
            
            // Get lists from eMailPlatform by Model
            $result = $this->_emailplatform->GetEmailplatformFields();
                        
            if (!is_array($result) OR empty($result)) {
                $this->_logger->info($result.' or no fields in eMailPlatform');
            } else {
                foreach ($result as $item) {
                    if($result['type'] = 'text'){
                        $this->_fields[] = array(
                            'value' => $item['fieldid'],
                            'label' => $item['fieldname']
                        );
                    }
                }
            }
            
            $this->_options = $this->_fields;
            
        }
        
        $options = $this->_options;
        if (!$isMultiselect) {
            array_unshift($options, array(
                'value' => '',
                'label' => __('--Please Select--')
            ));
        }
        
        $this->_storeManager->setCurrentStore(0);
        
        return $options;
        
    }

}

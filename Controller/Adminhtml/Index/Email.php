<?php

namespace Hestaworks\OrderEmailTester\Controller\Adminhtml\Index;


use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Email extends Action
{
	public function __construct(
		\Hestaworks\OrderEmailTester\Helper\Email $emailHelper,
		Context $context
	){
		$this->emailHelper = $emailHelper;
		parent::__construct($context);
	}
    public function execute()
    {
        echo $this->emailHelper->getEmailContent($this->getRequest()->getParam('id'));
    }
}

?>

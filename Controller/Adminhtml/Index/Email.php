<?php

namespace Hestaworks\OrderEmailTester\Controller\Adminhtml\Index;


class Email extends \Magento\Backend\App\Action
{
	public function __construct(
		\Hestaworks\OrderEmailTester\Helper\Email $emailHelper,
		\Magento\Backend\App\Action\Context $context
	){
		$this->emailHelper = $emailHelper;
		parent::__construct($context);
	}
    public function execute()
    {
        echo $this->emailHelper->getEmailContent($_POST['id']);
    }
}

?>
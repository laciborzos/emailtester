<?php

namespace Hestaworks\OrderEmailTester\Block\Adminhtml;

class Url extends \Magento\Backend\Block\Widget\Container
{

    public function __construct(
		\Magento\Backend\Helper\Data $urlHelper,
		\Magento\Backend\Block\Widget\Context $context,
		array $data = []
	)
    {
		$this->urlHelper = $urlHelper;
        parent::__construct($context, $data);
    }
	protected function _toHtml(){
		return "
		<div id='popup-modal'>			
		</div>
		<script type='text/javascript'>			
			var emailTesterSubmitUrl = '".$this->urlHelper->getUrl('orderemailtester/index/email')."';
		</script>";		
	}
}



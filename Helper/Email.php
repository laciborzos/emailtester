<?php 

namespace Hestaworks\OrderEmailTester\Helper;

class Email extends \Magento\Framework\App\Helper\AbstractHelper{
	
	public function __construct(
		\Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
		\Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
		\Magento\Payment\Helper\Data $paymentHelper,
		\Magento\Sales\Model\Order\Email\Container\OrderIdentity $identityContainer,
		\Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
	){
		$this->orderRepository = $orderRepository;
		$this->addressRenderer = $addressRenderer;
		$this->paymentHelper = $paymentHelper;
		$this->identityContainer = $identityContainer;
		$this->transportBuilder = $transportBuilder;
	}
	public function getEmailContent($orderId){
		$order = $this->orderRepository->get($orderId); /* get by order increment id, REFACTOR */
		if(!$order){
			return false;
		}
		$shippingAddress = $this->addressRenderer->format($order->getShippingAddress(), 'html');
		$billingAddress = $this->addressRenderer->format($order->getBillingAddress(), 'html');
		$paymentHtml = $this->paymentHelper->getInfoBlockHtml(
            $order->getPayment(),
            $order->getStore()->getStoreId()
        );
		$transport = [
			'order' => $order,
			'billing' => $order->getBillingAddress(),
			'payment_html' => $paymentHtml,
			'store' => $order->getStore(),
			'formattedShippingAddress' => $shippingAddress,
			'formattedBillingAddress' => $billingAddress
		];
		$transportObject = new \Magento\Framework\DataObject($transport);		
		$this->transportBuilder->addTo('laszlo.borzas@netlogiq.ro','Laszlo Borzas');
		$this->transportBuilder->setTemplateVars($transportObject->getData());
		$this->transportBuilder->setTemplateIdentifier($this->identityContainer->getTemplateId());
		$this->transportBuilder->setTemplateOptions([
					'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
					'store' => $order->getStore()->getStoreId()
				]);
		$transport = $this->transportBuilder->getTransport();
		return $transport->getMessage()->getBody()->getParts()[0]->getRawContent();
	}
		
}
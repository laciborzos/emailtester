<?php

namespace Hestaworks\OrderEmailTester\Helper;

use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Payment\Helper\Data;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Sales\Model\Order\Email\Container\OrderIdentity;

class Email extends AbstractHelper{

	public function __construct(
		OrderRepositoryInterface $orderRepository,
		Renderer $addressRenderer,
		Data $paymentHelper,
		OrderIdentity $identityContainer,
		TransportBuilder $transportBuilder
	){
		$this->orderRepository = $orderRepository;
		$this->addressRenderer = $addressRenderer;
		$this->paymentHelper = $paymentHelper;
		$this->identityContainer = $identityContainer;
		$this->transportBuilder = $transportBuilder;
	}
	public function getEmailContent($orderId){
	    try {
            $order = $this->orderRepository->get($orderId);
        }catch(NoSuchEntityException $entityException){
	        echo $entityException->getMessage();
	        return;
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
		$transportObject = new DataObject($transport);
		$this->transportBuilder->addTo('laszlo.borzas@netlogiq.ro','Laszlo Borzas');
		$this->transportBuilder->setTemplateVars($transportObject->getData());
		$this->transportBuilder->setTemplateIdentifier($this->identityContainer->getTemplateId());
		$this->transportBuilder->setTemplateOptions([
					'area' => Area::AREA_FRONTEND,
					'store' => $order->getStore()->getStoreId()
				]);
		$transport = $this->transportBuilder->getTransport();
		return $transport->getMessage()->getBody()->getParts()[0]->getRawContent();
	}

}

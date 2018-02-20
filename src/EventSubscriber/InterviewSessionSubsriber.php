<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Book;
use App\Entity\Interview\InterviewSession;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class InterviewSessionSubsriber implements EventSubscriberInterface {
	private $mailer;
	
	public function __construct() {
	
	}
	
	public static function getSubscribedEvents() {
		return [
			KernelEvents::VIEW => [ 'rectifyData', EventPriorities::PRE_VALIDATE ]
		];
	}
	
	public function rectifyData(GetResponseForControllerResultEvent $event) {
		$session = $event->getControllerResult();
		$method  = $event->getRequest()->getMethod();
		
		if($session instanceof InterviewSession && Request::METHOD_POST === $method) {
			$request = $event->getRequest();
			
			$session->initiateData();
			
			return;
		}
		
	}
}
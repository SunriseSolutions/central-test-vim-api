<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Interview\InterviewSession;
use App\Service\UserService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class InterviewSessionSubscriber implements EventSubscriberInterface {
	private $mailer;
	private $recruiterService;
	
	public function __construct(UserService $rs) {
		$this->recruiterService = $rs;
	}
	
	public static function getSubscribedEvents() {
		return [
			KernelEvents::VIEW     => [
				'rectifyData',
				EventPriorities::PRE_VALIDATE,
			],
			KernelEvents::RESPONSE => [
				'isGrantedView'
			],
			
			KernelEvents::REQUEST => [
				'filterData',
				EventPriorities::PRE_READ
			],
		];
	}
	
	public function filterData(GetResponseEvent $event) {
		$request    = $event->getRequest();
		$controller = $request->attributes->get('_controller');
		$class      = $request->attributes->get('_api_resource_class');
		if($request->isMethod('get') && $class === InterviewSession::class) {
			$rs = $this->recruiterService;
			if($controller === 'api_platform.action.get_collection') {
				$request->query->add([ 'recruiter' => $rs->getUsername() ]);
			} elseif($controller === 'api_platform.action.get_item') {
			
			}
		}
	}
	
	
	public function isGrantedView(FilterResponseEvent $event) {
		$request  = $event->getRequest();
		$response = $event->getResponse();

//		var_dump($request);
//		exit();
		$controller = $request->attributes->get('_controller');
		$class      = $request->attributes->get('_api_resource_class');
		if($request->isMethod('get') && $class === InterviewSession::class) {
			$rs = $this->recruiterService;
			if($controller === 'api_platform.action.get_collection') {
			
			} elseif($controller === 'api_platform.action.get_item') {
				$username = $rs->getUsername();
				/** @var InterviewSession $session */
				$session = $request->attributes->get('data');
				
				if($username !== $session->getUsername()) {
					throw new UnauthorizedHttpException('Bearer CANDIDATE','You are not authorised to access this Invitation');
				}
//				if($session->getUsername() !== $username ||)
				
				
			}
		}
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
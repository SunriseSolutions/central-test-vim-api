<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Interview\InterviewSetting;
use App\Service\UserService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class InterviewSettingSubscriber implements EventSubscriberInterface {
	
	/** @var UserService */
	private $recruiterService;
	
	public function __construct(UserService $rs) {
		$this->recruiterService = $rs;
	}
	
	public static function getSubscribedEvents() {
		return [
			KernelEvents::REQUEST => [ 'filterData', EventPriorities::PRE_READ ]
		];
	}
	
	public function filterData(GetResponseEvent $event) {
		$request    = $event->getRequest();
		$controller = $request->attributes->get('_controller');
		$class      = $request->attributes->get('_api_resource_class');
		if($request->isMethod('get') && $class === InterviewSetting::class && $controller === 'api_platform.action.get_collection') {
			$rs = $this->recruiterService;
			
			$request->query->add([ 'recruiter' => strval($rs->getUsername()) ]);
		}
		
		
	}
}
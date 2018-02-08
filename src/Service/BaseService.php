<?php

namespace App\Service;


use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseService {
	protected $container;
	
	function __construct(ContainerInterface $container) {
		$this->container = $container;
	}
	
	/**
	 * @return \Doctrine\Bundle\DoctrineBundle\Registry|object
	 */
	protected function getDoctrine() {
		return $this->container->get('doctrine');
	}
	
	protected function get($service) {
		return $this->container->get($service);
	}
}
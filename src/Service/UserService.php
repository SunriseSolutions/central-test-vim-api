<?php

namespace App\Service;

use App\Entity\Recruitment\Recruiter;
use App\Service\BaseService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserService extends BaseService {
	/**
	 * @var JWTManager
	 */
	private $jwtManager;
	
	function __construct(JWTManager $jwtManager, ContainerInterface $container) {
		parent::__construct($container);
		$this->jwtManager = $jwtManager;
	}
	
	public function initiateRecruiter($recruiterId, $recruiterEmail) {
		$recruiterRepo = $this->getDoctrine()->getRepository(Recruiter::class);
		/** @var Recruiter $recruiter */
		$recruiter = $recruiterRepo->findOneBy([ 'adminEmail' => $recruiterEmail, 'recruiterId' => $recruiterId ]);
		
		/** @var EntityManagerInterface $em */
		$em = $this->get('doctrine.orm.default_entity_manager');
		if(empty($recruiter)) {
			$recruiter = new Recruiter();
			$recruiter->setAdminEmail($recruiterEmail);
			$recruiter->setRecruiterId($recruiterId);
			$recruiter->initiateEmployerCode();
			$em->persist($recruiter);
			$em->flush();
		}
		
		return $recruiter;
	}
	
	public function getUser() {
//		$request = $this->container->get('request_stack')->getCurrentRequest();
//		$bearer  = $request->headers->get('Authorization');
//		if(empty($bearer)) {
//			return null;
//		}
//
//		$bearerStr = 'Bearer ';
//		if(strpos($bearer, $bearerStr) < 0) {
//			return null;
//		}
		
		$ts          = $this->container->get('security.token_storage');
		$token       = $ts->getToken();
		$credentials = $token->getCredentials();
		if(empty($token)) {
			return null;
		}
		
		return $this->jwtManager->decode($token);
	}
	
	public function getUsername() {
		$payload = $this->getUser();
		
		return strval($payload[ $this->getUserIdentityField() ]);
	}
	
	public function getRoles() {
		$payload = $this->getUser();
		
		return $payload['roles'];
	}
	
	public function getUserIdentityField() {
		return $this->jwtManager->getUserIdentityField();
	}
	
	public function generateTokenFromRecruiter(Recruiter $recruiter) {
		return $this->jwtManager->create($recruiter);
	}
}
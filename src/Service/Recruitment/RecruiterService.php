<?php

namespace App\Service\Recruitment;

use App\Entity\Recruitment\Recruiter;
use App\Service\BaseService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RecruiterService extends BaseService {
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
	
	public function generateTokenFromRecruiter(Recruiter $recruiter) {
		return $this->jwtManager->create($recruiter);
	}
}
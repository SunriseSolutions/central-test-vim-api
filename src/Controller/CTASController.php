<?php

namespace App\Controller;

use App\Entity\Recruitment\Recruiter;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CTASController extends Controller {
	
	/**
	 * @Route("/ctas/initiate-employer", name="app_api_employer_signup"
	 * )
	 */
	public function initiateRecruiter(Request $request) {
		$recruiterId   = $request->get('adminEmail', '00000');
		$adminEmail    = $request->get('adminEmail', 'noadmin-provided@gmail.com');
		$recruiterRepo = $this->getDoctrine()->getRepository(Recruiter::class);
		/** @var Recruiter $recruiter */
		$recruiter = $recruiterRepo->findOneBy([ 'adminEmail' => $adminEmail, 'recruiterId' => $recruiterId ]);
		$em        = $this->get('doctrine.orm.default_entity_manager');
		if(empty($recruiter)) {
			$recruiter = new Recruiter();
			$recruiter->setAdminEmail($adminEmail);
			$recruiter->setRecruiterId($recruiterId);
			$recruiter->initiateEmployerCode();
			$em->persist($recruiter);
			$em->flush();
		}
		
		return new JsonResponse([ 'ok' . $recruiterId . '  ' . $adminEmail ]);
	}
}
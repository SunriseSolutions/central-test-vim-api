<?php

namespace App\Controller;

use App\Entity\Recruitment\Recruiter;
use App\Service\Recruitment\RecruiterService;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CTASController extends Controller {
	
	/**
	 * @Route("/ctas/recruiter/initiate", name="app_api_recruiter_initiate"
	 * )
	 */
	public function initiateRecruiter(Request $request) {
		$recruiterId = $request->get('_username', '0');
		$adminEmail  = $request->get('_password', 'noadmin-provided@gmail.com');
		$jwt         = $this->get('app.recruiter')->initiateRecruiter($recruiterId, $adminEmail);
		
		return new JsonResponse([ "token" => $jwt ]);
	}
}
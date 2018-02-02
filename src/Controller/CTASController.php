<?php

namespace App\Controller;

use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CTASController extends Controller {
	
	/**
	 * @Route("/api/ctas/employer-login/{adminEmail}/{employerId}", name="app_api_employer_login", requirements={"adminEmail":".+"})
	 */
	public function employerLogin($employerId, $adminEmail, Request $request) {
		
		return new JsonResponse([ 'ok'.$employerId.'  '.$adminEmail ]);
	}
}
<?php

namespace App\Controller;

use App\Entity\Interview\InterviewSetting;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecretController extends Controller {
	/**
	 * @Route("/binhle/secret-vault/{max}", name="app_secret_vault")
	 */
	public function number($max) {
		$em = $this->get('doctrine.orm.default_entity_manager');
//		$category = new InterviewSetting();
//		$category->setClientCode('123');
//		$category->setLogoUrl('http://www.google.com');
//
//		$category->translate('fr')->setTitle('Chaussures');
//		$category->translate('fr')->setThankyouMessage('Merci bcppp');
//		$category->translate('en')->setTitle('Shoes');
//		$category->translate('en')->setThankyouMessage('Thankyou very muchhhh');
//		$em->persist($category);
//		$category->mergeNewTranslations();
//		$em->flush();
		
		$repo = $this->getDoctrine()->getRepository(InterviewSetting::class);
		/** @var InterviewSetting $category */
		$category = $repo->findAll()[0];
		
		$refreshTokenRepo = $this->getDoctrine()->getRepository(RefreshToken::class);
		
		/** @var RefreshToken $rToken */
		$rToken = $refreshTokenRepo->findOneBy([ 'username' => 'ctas' ], [ 'id' => 'DESC' ]);
		
		// In order to persist new translations, call mergeNewTranslations method, before flush
		
		$title  = $category->translate('en')->getTitle();
		$number = mt_rand(0, $max);
		if($rToken->getValid() > new \DateTime()) {
			$rTokenValid = 'rToken Valid';
		} else {
			$rTokenValid = 'rToken NOT Valid';
			$rToken->setValid(clone $rToken->getValid());
			$rToken->getValid()->modify('+1 year');
			$this->get('gesdinet.jwtrefreshtoken.refresh_token_manager')->save($rToken, true);
			
			$em->persist($rToken);
			$em->flush($rToken);
			$em->flush();
			
			$em->detach($rToken);
		}
		/** @var RefreshToken $rToken */
		$rToken2 = $refreshTokenRepo->findOneBy([ 'refreshToken' => '3820ff7d9b98d605c33e216a2edd6bc3c725497f138b6e60f91fc1443f0d80b0bed351074f4c71ddef2503172400c061cf7ea628c36348f7bda9cb4a685c4b8c'], [ 'id' => 'DESC' ]);
		
		return new Response(
			'<html><body>Lucky number: ' . $number . ' --- ' . $title . '<br/> Expired DateTime ' . $rToken2->getValid()->format('d/m/Y') . ' --- ' . $rToken->getRefreshToken() . ' --- ' . $rTokenValid . '</body></html>'
		);
	}
}
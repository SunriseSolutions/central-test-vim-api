<?php

namespace App\Controller;


use App\Entity\Interview\InterviewSetting;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecretController extends Controller {
	/**
	 * @Route("/binhle/secret-vault/{max}", name="app_secret_vault")
	 */
	public function number($max) {
		$em       = $this->get('doctrine.orm.default_entity_manager');
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
		
		// In order to persist new translations, call mergeNewTranslations method, before flush
		
		$title  = $category->translate('en')->getTitle();
		$number = mt_rand(0, $max);
		
		return new Response(
			'<html><body>Lucky number: ' . $number . ' --- ' . $title . '</body></html>'
		);
	}
}
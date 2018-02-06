<?php

namespace App\Entity\Interview;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * To translate the InterviewQuestion.
 * @ApiResource(attributes={
 *     "filters"={"interview_question__translation.search_filter","entity__translation.search_filter", "entity__translation.boolean_filter"},
 * },
)
 * @ORM\Entity()
 * @ORM\Table(name="interview__setting_translation")
 */
class InterviewQuestionTranslation {
	
	use ORMBehaviors\Translatable\Translation;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=125)
	 */
	protected $name;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=125)
	 */
	protected $text;
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @param string $name
	 */
	public function setName(string $name): void {
		$this->name = $name;
	}
	
	/**
	 * @return string
	 */
	public function getText(): string {
		return $this->text;
	}
	
	/**
	 * @param string $text
	 */
	public function setText(string $text): void {
		$this->text = $text;
	}
	
	
}
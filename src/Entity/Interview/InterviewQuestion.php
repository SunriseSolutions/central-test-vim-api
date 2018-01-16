<?php

namespace App\Entity\Interview;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity()
 * @ORM\Table(name="interview__question")
 */
class InterviewQuestion {
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @var InterviewSetting
	 * @ORM\ManyToOne(targetEntity="InterviewSetting",inversedBy="questions", fetch="EAGER")
	 * @ORM\JoinColumn(name="id_setting", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $setting;
	
	/**
	 * @var integer
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected $readingTimeLimit;
	
	/**
	 * @var integer
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected $answerTimeLimit;
	
	/**
	 * @var string
	 * @ORM\Column(type="integer")
	 */
	protected $position = 1;
	
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
	 * @return InterviewSetting
	 */
	public function getSetting(): InterviewSetting {
		return $this->setting;
	}
	
	/**
	 * @param InterviewSetting $setting
	 */
	public function setSetting(InterviewSetting $setting): void {
		$this->setting = $setting;
	}
	
	/**
	 * @return string
	 */
	public function getPosition(): string {
		return $this->position;
	}
	
	/**
	 * @param string $position
	 */
	public function setPosition(string $position): void {
		$this->position = $position;
	}
	
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
	
	/**
	 * @return int
	 */
	public function getReadingTimeLimit(): int {
		return $this->readingTimeLimit;
	}
	
	/**
	 * @param int $readingTimeLimit
	 */
	public function setReadingTimeLimit(int $readingTimeLimit): void {
		$this->readingTimeLimit = $readingTimeLimit;
	}
	
	/**
	 * @return int
	 */
	public function getAnswerTimeLimit(): int {
		return $this->answerTimeLimit;
	}
	
	/**
	 * @param int $answerTimeLimit
	 */
	public function setAnswerTimeLimit(int $answerTimeLimit): void {
		$this->answerTimeLimit = $answerTimeLimit;
	}
}
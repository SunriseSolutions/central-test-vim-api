<?php

namespace App\Entity\Interview;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ApiResource(attributes={
 *     "access_control"="is_granted('ROLE_RECRUITER')",
 *     "order"={"position": "ASC"},
 *     "normalization_context"={"groups"={"read_interview_setting"}},
 *     "denormalization_context"={"groups"={"write_interview_setting"}}
 * },
)
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
	
	use ORMBehaviors\Translatable\Translatable;
	
	/**
	 * @var array
	 * @Groups({"read_interview_setting","write_interview_setting"})
	 */
	protected $translations;
	
	
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @Groups({"read_interview_setting"})
	 */
	protected $id;
	
	/**
	 * @var InterviewSetting
	 * @ORM\ManyToOne(targetEntity="InterviewSetting",inversedBy="questions", fetch="EAGER")
	 * @ORM\JoinColumn(name="id_setting", referencedColumnName="id", onDelete="CASCADE")
	 * @Groups({"read_interview_setting","write_interview_setting"})
	 */
	protected $setting;
	
	/**
	 * @var integer
	 * @ORM\Column(type="integer",nullable=true)
	 * @Groups({"read_interview_setting","write_interview_setting"})
	 */
	protected $readingTimeLimit;
	
	/**
	 * @var integer
	 * @ORM\Column(type="integer",nullable=true)
	 * @Groups({"read_interview_setting","write_interview_setting"})
	 */
	protected $answerTimeLimit;
	
	/**
	 * @var string
	 * @ORM\Column(type="integer")
	 * @Groups({"read_interview_setting","write_interview_setting"})
	 */
	protected $position = 1;
	
	/**
	 * @return InterviewSetting
	 */
	public function getSetting(): ?InterviewSetting {
		return $this->setting;
	}
	
	/**
	 * @param InterviewSetting $setting
	 */
	public function setSetting(InterviewSetting $setting = null): void {
		$this->setting = $setting;
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

}
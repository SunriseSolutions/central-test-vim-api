<?php

namespace App\Entity\Interview;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Recruitment\Recruiter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * To hold data about the InterviewSetting.
 * @ApiResource(attributes={
 *     "access_control"="is_granted('ROLE_RECRUITER')",
 *     "filters"={"interview_setting.search_filter"},
 *     "order"={"updatedAt": "DESC","createdAt": "DESC"},
 *     "normalization_context"={"groups"={"read_interview_setting"}},
 *     "denormalization_context"={"groups"={"write_interview_setting"}}
 * },
)
 *
 * @ORM\Entity()
 * @ORM\Table(name="interview__setting")
 */
class InterviewSetting {
	
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
	
	function __construct() {
		$this->questions = new ArrayCollection();
		$this->sessions  = new ArrayCollection();
		$this->createdAt = new \DateTime();
	}
	
	/**
	 * @var Recruiter
	 * @ORM\ManyToOne(targetEntity="App\Entity\Recruitment\Recruiter",inversedBy="interviews")
	 * @ORM\JoinColumn(name="id_recruiter", referencedColumnName="id", onDelete="CASCADE")
	 * @Groups({"read_interview_setting","write_interview_setting"})
	 */
	protected $recruiter;
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="InterviewSession", mappedBy="setting")
	 * ApiSubresource()
	 *
	 */
	protected $sessions;
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="InterviewQuestion", mappedBy="setting", cascade={"all"}, orphanRemoval=true)
	 * ApiSubresource()
	 * @ApiProperty(attributes={"fetchEager": true})
	 * @Groups({"read_interview_setting","write_interview_setting"})
	 */
	protected $questions;
	
	public function addQuestion(InterviewQuestion $question) {
		$this->questions->add($question);
		$question->setSetting($this);
	}
	
	public function removeQuestion(InterviewQuestion $question) {
		$this->questions->removeElement($question);
		$question->setSetting(null);
	}
	
	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 * @Groups({"read_interview_setting"})
	 */
	protected $createdAt;
	
	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $updatedAt;
	
	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $enabled = true;
	
	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 * @Groups({"read_interview_setting","write_interview_setting"})
	 */
	protected $creatorId;
	
	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 * @Groups({"read_interview_setting","write_interview_setting"})
	 */
	protected $expireIn = 24;
	
	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 * @Groups({"read_interview_setting","write_interview_setting"})
	 */
	protected $readingTimeLimit = 30;
	
	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 * @Groups({"read_interview_setting","write_interview_setting"})
	 */
	protected $answerTimeLimit = 180;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=500, nullable=true)
	 */
	protected $logoUrl;
	
	///////////////////////////////////////
	///
	///
	///
	///////////////////////////////////////
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @return bool
	 */
	public function isEnabled(): bool {
		return $this->enabled;
	}
	
	/**
	 * @param bool $enabled
	 */
	public function setEnabled(bool $enabled): void {
		$this->enabled = $enabled;
	}
	
	/**
	 * @return int
	 */
	public function getExpireIn(): int {
		return $this->expireIn;
	}
	
	/**
	 * @param int $expireIn
	 */
	public function setExpireIn(int $expireIn): void {
		$this->expireIn = $expireIn;
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
	 * @return Recruiter
	 */
	public function getRecruiter(): ?Recruiter {
		return $this->recruiter;
	}
	
	/**
	 * @param Recruiter $recruiter
	 */
	public function setRecruiter($recruiter): void {
		$this->recruiter = $recruiter;
	}
	
	/**
	 * @return Collection
	 */
	public function getSessions(): ?Collection {
		return $this->sessions;
	}
	
	/**
	 * @param Collection $sessions
	 */
	public function setSessions(Collection $sessions): void {
		$this->sessions = $sessions;
	}
	
	/**
	 * @return Collection
	 */
	public function getQuestions(): ?Collection {
		return $this->questions;
	}
	
	/**
	 * @param Collection $questions
	 */
	public function setQuestions($questions): void {
		$this->questions = $questions;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getCreatedAt(): \DateTime {
		return $this->createdAt;
	}
	
	/**
	 * @param \DateTime $createdAt
	 */
	public function setCreatedAt(\DateTime $createdAt): void {
		$this->createdAt = $createdAt;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getUpdatedAt(): ?\DateTime {
		return $this->updatedAt;
	}
	
	/**
	 * @param \DateTime $updatedAt
	 */
	public function setUpdatedAt(\DateTime $updatedAt): void {
		$this->updatedAt = $updatedAt;
	}
	
	/**
	 * @return string
	 */
	public function getLogoUrl(): ?string {
		return $this->logoUrl;
	}
	
	/**
	 * @param string $logoUrl
	 */
	public function setLogoUrl(string $logoUrl): void {
		$this->logoUrl = $logoUrl;
	}
	
	/**
	 * @return int
	 */
	public function getCreatorId(): int {
		return $this->creatorId;
	}
	
	/**
	 * @param int $creatorId
	 */
	public function setCreatorId(int $creatorId): void {
		$this->creatorId = $creatorId;
	}
	
}
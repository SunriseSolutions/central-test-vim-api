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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * To hold data about the InterviewSetting.
 * @ApiResource(attributes={
 * },
 *     iri="http://schema.org/Book"
 *     )
 *
 * @ORM\Entity()
 * @ORM\Table(name="interview__setting")
 */
class InterviewSetting {
	
	use ORMBehaviors\Translatable\Translatable;
	
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	function __construct() {
		$this->questions = new ArrayCollection();
		$this->sessions  = new ArrayCollection();
	}
	
	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $enabled = true;
	
	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $expireIn = 24;
	
	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $readingTimeLimit = 30;
	
	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $answerTimeLimit = 180;
	
	/**
	 * @var Recruiter
	 * @ORM\ManyToOne(targetEntity="App\Entity\Recruitment\Recruiter",inversedBy="interviews")
	 * @ORM\JoinColumn(name="id_recruiter", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $recruiter;
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="InterviewSession", mappedBy="setting")
	 * @ApiSubresource()
	 */
	protected $sessions;
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="InterviewQuestion", mappedBy="setting", cascade={"all"}, orphanRemoval=true)
	 * @ApiSubresource()
	 */
	protected $questions;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=500)
	 */
	protected $title;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=500)
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
	public function getSessions(): Collection {
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
	public function getQuestions(): Collection {
		return $this->questions;
	}
	
	/**
	 * @param Collection $questions
	 */
	public function setQuestions(Collection $questions): void {
		$this->questions = $questions;
	}
	
	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}
	
	/**
	 * @param string $title
	 */
	public function setTitle(string $title): void {
		$this->title = $title;
	}
	
	/**
	 * @return string
	 */
	public function getLogoUrl(): string {
		return $this->logoUrl;
	}
	
	/**
	 * @param string $logoUrl
	 */
	public function setLogoUrl(string $logoUrl): void {
		$this->logoUrl = $logoUrl;
	}
	
}
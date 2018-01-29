<?php

namespace App\Entity\Interview;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ApiResource()
 *
 * @ORM\Entity()
 * @ORM\Table(name="interview__session")
 */
class InterviewSession implements UserInterface, \Serializable {
	
	/////// Start of UserInterface Impl ///////
	public function getUsername() {
		return $this->candidateCode;
	}
	
	public function getSalt() {
		// you *may* need a real salt depending on your encoder
		// see section on salt below
		return null;
	}
	
	public function getPassword() {
		return $this->employerCode;
	}
	
	public function getRoles() {
		return array( 'ROLE_CANDIDATE' );
	}
	
	public function eraseCredentials() {
	}
	
	/** @see \Serializable::serialize() */
	public function serialize() {
		return serialize(array(
			$this->id,
			
			$this->candidateCode,
			$this->employerCode,
			
			// see section on salt below
			// $this->salt,
		));
	}
	
	/** @see \Serializable::unserialize() */
	public function unserialize($serialized) {
		list (
			$this->id,
			
			$this->candidateCode,
			$this->employerCode,
			// see section on salt below
			// $this->salt
			) = unserialize($serialized);
	}
	/////// End of UserInterface Impl ///////
	///
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	function __construct() {
		$this->answers = new ArrayCollection();
	}
	
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @var InterviewSession
	 * @ORM\OneToOne(targetEntity="InterviewSession", mappedBy="actualSession", cascade={"all"}, orphanRemoval=true)
	 * @ApiSubresource()
	 */
	protected $practiceSession;
	
	/**
	 * @var InterviewSession
	 * @ORM\OneToOne(targetEntity="InterviewSession", inversedBy="practiceSession", cascade={"persist","merge"})
	 * @ORM\JoinColumn(name="id_actual_session", referencedColumnName="id")*
	 * @ApiSubresource()
	 */
	protected $actualSession;
	
	/**
	 * @var InterviewSetting
	 * @ORM\ManyToOne(targetEntity="InterviewSetting",inversedBy="sessions")
	 * @ORM\JoinColumn(name="id_setting", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $setting;
	
	/**
	 * E:\xampp71\htdocs\projects\inspot\vendor\api-platform\core\src\Swagger\Serializer\DocumentationNormalizer.php
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="InterviewAnswer", mappedBy="session", cascade={"persist","merge"}, orphanRemoval=true)
	 * @ApiSubresource()
	 */
	protected $answers;
	
	/**
	 * @var \DateTime
	 * @ORM\Column(type="utc_datetime")
	 */
	protected $deadline;
	
	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $readingTimeLimit;
	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $answerTimeLimit;
	
	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $completed = false;
	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $enabled = true;
	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $mock = false;
	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $started = false;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 */
	protected $candidateCode;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 */
	protected $employerCode;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 */
	protected $clientCode;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 */
	protected $logoUrl;
	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $thankyouMessage;
	
	/**
	 * @return InterviewSession
	 */
	public function getPracticeSession(): ?InterviewSession {
		return $this->practiceSession;
	}
	
	/**
	 * @param InterviewSession $practiceSession
	 */
	public function setPracticeSession(InterviewSession $practiceSession): void {
		$this->practiceSession = $practiceSession;
	}
	
	/**
	 * @return InterviewSession
	 */
	public function getActualSession(): ?InterviewSession {
		return $this->actualSession;
	}
	
	/**
	 * @param InterviewSession $actualSession
	 */
	public function setActualSession(InterviewSession $actualSession): void {
		$this->actualSession = $actualSession;
	}
	
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
	 * @return Collection
	 */
	public function getAnswers(): Collection {
		return $this->answers;
	}
	
	/**
	 * @param Collection $answers
	 */
	public function setAnswers(Collection $answers): void {
		$this->answers = $answers;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getDeadline(): \DateTime {
		return $this->deadline;
	}
	
	/**
	 * @param \DateTime $deadline
	 */
	public function setDeadline(\DateTime $deadline): void {
		$this->deadline = $deadline;
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
	 * @return bool
	 */
	public function isCompleted(): bool {
		return $this->completed;
	}
	
	/**
	 * @param bool $completed
	 */
	public function setCompleted(bool $completed): void {
		$this->completed = $completed;
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
	 * @return bool
	 */
	public function isMock(): bool {
		return $this->mock;
	}
	
	/**
	 * @param bool $mock
	 */
	public function setMock(bool $mock): void {
		$this->mock = $mock;
	}
	
	/**
	 * @return bool
	 */
	public function isStarted(): bool {
		return $this->started;
	}
	
	/**
	 * @param bool $started
	 */
	public function setStarted(bool $started): void {
		$this->started = $started;
	}
	
	/**
	 * @return string
	 */
	public function getCandidateCode(): string {
		return $this->candidateCode;
	}
	
	/**
	 * @param string $candidateCode
	 */
	public function setCandidateCode(string $candidateCode): void {
		$this->candidateCode = $candidateCode;
	}
	
	/**
	 * @return string
	 */
	public function getEmployerCode(): string {
		return $this->employerCode;
	}
	
	/**
	 * @param string $employerCode
	 */
	public function setEmployerCode(string $employerCode): void {
		$this->employerCode = $employerCode;
	}
	
	/**
	 * @return string
	 */
	public function getClientCode(): string {
		return $this->clientCode;
	}
	
	/**
	 * @param string $clientCode
	 */
	public function setClientCode(string $clientCode): void {
		$this->clientCode = $clientCode;
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
	
	/**
	 * @return string
	 */
	public function getThankyouMessage(): string {
		return $this->thankyouMessage;
	}
	
	/**
	 * @param string $thankyouMessage
	 */
	public function setThankyouMessage(string $thankyouMessage): void {
		$this->thankyouMessage = $thankyouMessage;
	}
	
}
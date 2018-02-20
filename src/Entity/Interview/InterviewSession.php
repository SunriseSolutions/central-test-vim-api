<?php

namespace App\Entity\Interview;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Recruitment\Recruiter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(attributes={
 *     "filters"={"interview_session.search_filter"},
 *     "order"={"updatedAt": "DESC","createdAt": "DESC"},
 *     "normalization_context"={"groups"={"read_interview_session"}},
 *     "denormalization_context"={"groups"={"write_interview_session"}}
 * },
)
 *
 * @ORM\Entity()
 * @ORM\Table(name="interview__session")
 */
class InterviewSession implements UserInterface, \Serializable {
	public function initiateData() {
		if(empty($this->locale)) {
			$this->locale = 'en';
		}
		$this->copyFromSetting();
		$this->initiateCandidateCode();
		$this->employerCode = $this->recruiter->getEmployerCode();
	}
	
	public function initiateCandidateCode() {
		if(empty($this->candidateCode)) {
			$this->candidateCode = $this->recruiter->generate20DigitCode();
		}
		
		return $this->candidateCode;
	}
	
	public function copyFromSetting() {
		if( ! empty($this->setting)) {
			$vars = get_object_vars($this);
			foreach($vars as $key => $value) {
				if(empty($value) && property_exists($this->setting, $key)) {
					$getter     = 'get' . ucfirst($key);
					$this->$key = $this->setting->$getter();
				}
			}
			$this->deadline = new \DateTime();
			$this->deadline->modify('+ ' . $this->setting->getExpireIn() . ' days');
			
			/** @var InterviewSettingTranslation $translation */
			$translation           = $this->setting->translate($this->locale);
			$this->title           = $translation->getTitle();
			$this->thankyouMessage = $translation->getThankyouMessage();
		}
	}
	
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
	function __construct() {
		$this->answers   = new ArrayCollection();
		$this->createdAt = new \DateTime();
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
	 * ApiSubresource()
	 */
	protected $practiceSession;
	
	/**
	 * @var InterviewSession
	 * @ORM\OneToOne(targetEntity="InterviewSession", inversedBy="practiceSession", cascade={"persist","merge"})
	 * @ORM\JoinColumn(name="id_actual_session", referencedColumnName="id")*
	 * ApiSubresource()
	 */
	protected $actualSession;
	
	/**
	 * @var Recruiter
	 * @ORM\ManyToOne(targetEntity="App\Entity\Recruitment\Recruiter",inversedBy="sessions")
	 * @ORM\JoinColumn(name="id_recruiter", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $recruiter;
	
	/**
	 * @var InterviewSetting
	 * @ORM\ManyToOne(targetEntity="InterviewSetting",inversedBy="sessions")
	 * @ORM\JoinColumn(name="id_setting", referencedColumnName="id", onDelete="SET NULL")
	 * @Groups({"read_interview_session","write_interview_session"})
	 */
	protected $setting;
	
	/**
	 * E:\xampp71\htdocs\projects\inspot\vendor\api-platform\core\src\Swagger\Serializer\DocumentationNormalizer.php
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="InterviewAnswer", mappedBy="session", cascade={"persist","merge"}, orphanRemoval=true)
	 * ApiSubresource()
	 */
	protected $answers;
	
	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	protected $createdAt;
	
	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $updatedAt;
	
	
	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 * @Groups({"read_interview_session","write_interview_session"})
	 */
	protected $deadline;
	
	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 * @Groups({"read_interview_session","write_interview_session"})
	 */
	protected $readingTimeLimit;
	
	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 * @Groups({"read_interview_session","write_interview_session"})
	 */
	protected $answerTimeLimit;
	
	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 * @Groups({"read_interview_session"})
	 */
	protected $completed = false;
	
	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 * @Groups({"read_interview_session","write_interview_session"})
	 */
	protected $enabled = true;
	
	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 * @Groups({"read_interview_session"})
	 */
	protected $mock = false;
	
	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 * @Groups({"read_interview_session"})
	 */
	protected $started = false;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=5)
	 * @Groups({"read_interview_session","write_interview_session"})
	 */
	protected $locale;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=500)
	 * @Groups({"read_interview_session","write_interview_session"})
	 */
	protected $title;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 * @Groups({"read_interview_session"})
	 */
	protected $candidateCode;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 * @Groups({"read_interview_session"})
	 */
	protected $employerCode;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 * @Groups({"read_interview_session","write_interview_session"})
	 */
	protected $candidateId;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 * @Groups({"read_interview_session","write_interview_session"})
	 */
	protected $candidateEmail;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 * @Groups({"read_interview_session","write_interview_session"})
	 */
	protected $candidateName;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @Groups({"read_interview_session","write_interview_session"})
	 */
	protected $logoUrl;
	
	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
	 * @Groups({"read_interview_session","write_interview_session"})
	 */
	protected $thankyouMessage;
////////////////////////////////////////////
///
/// DO NOT DELETE getId()
///
////////////////////////////////////////////
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @return InterviewSession
	 */
	public function getPracticeSession(): InterviewSession {
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
	public function getActualSession(): InterviewSession {
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
	public function getCandidateId(): string {
		return $this->candidateId;
	}
	
	/**
	 * @param string $candidateId
	 */
	public function setCandidateId(string $candidateId): void {
		$this->candidateId = $candidateId;
	}
	
	/**
	 * @return string
	 */
	public function getCandidateName(): string {
		return $this->candidateName;
	}
	
	/**
	 * @param string $candidateName
	 */
	public function setCandidateName(string $candidateName): void {
		$this->candidateName = $candidateName;
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
	 * @return string
	 */
	public function getThankyouMessage(): ?string {
		return $this->thankyouMessage;
	}
	
	/**
	 * @param string $thankyouMessage
	 */
	public function setThankyouMessage(string $thankyouMessage): void {
		$this->thankyouMessage = $thankyouMessage;
	}
	
	/**
	 * @return Recruiter
	 */
	public function getRecruiter(): Recruiter {
		return $this->recruiter;
	}
	
	/**
	 * @param Recruiter $recruiter
	 */
	public function setRecruiter(Recruiter $recruiter): void {
		$this->recruiter = $recruiter;
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
	public function getCandidateEmail(): string {
		return $this->candidateEmail;
	}
	
	/**
	 * @param string $candidateEmail
	 */
	public function setCandidateEmail(string $candidateEmail): void {
		$this->candidateEmail = $candidateEmail;
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
	public function getUpdatedAt(): \DateTime {
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
	public function getLocale(): string {
		return $this->locale;
	}
	
	/**
	 * @param string $locale
	 */
	public function setLocale(string $locale): void {
		$this->locale = $locale;
	}
	
}
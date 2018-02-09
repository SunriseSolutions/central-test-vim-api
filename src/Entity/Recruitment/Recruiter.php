<?php

namespace App\Entity\Recruitment;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * To hold data about the Recruiter/Employer.
 * @ApiResource(attributes={
 *     "access_control"="is_granted('ROLE_SUPER_ADMIN') or object.recruiterId == user.username",
 *     "normalization_context"={"groups"={"read_recruiter"}},
 *     "denormalization_context"={"groups"={"write_recruiter"}}
 * },
)
 
 * @ORM\Entity()
 * @ORM\Table(name="recruitment__recruiter")
 */
class Recruiter implements UserInterface, \Serializable {
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/** @see \Serializable::serialize() */
	public function serialize() {
		return serialize(array(
			$this->id,
			
			$this->recruiterId,
			$this->adminEmail,
			
			// see section on salt below
			// $this->salt,
		));
	}
	
	/** @see \Serializable::unserialize() */
	public function unserialize($serialized) {
		list (
			$this->id,
			
			$this->recruiterId,
			$this->adminEmail,
			// see section on salt below
			// $this->salt
			) = unserialize($serialized);
	}
	
	/**
	 * Returns the roles granted to the user.
	 *
	 * <code>
	 * public function getRoles()
	 * {
	 *     return array('ROLE_USER');
	 * }
	 * </code>
	 *
	 * Alternatively, the roles might be stored on a ``roles`` property,
	 * and populated in any number of different ways when the user object
	 * is created.
	 *
	 * @return (Role|string)[] The user roles
	 */
	public function getRoles() {
		return array( 'ROLE_RECRUITER' );
	}
	
	/**
	 * Returns the password used to authenticate the user.
	 *
	 * This should be the encoded password. On authentication, a plain-text
	 * password will be salted, encoded, and then compared to this value.
	 *
	 * @return string The password
	 */
	public function getPassword() {
		return $this->adminEmail;
	}
	
	/**
	 * Returns the salt that was originally used to encode the password.
	 *
	 * This can return null if the password was not encoded using a salt.
	 *
	 * @return string|null The salt
	 */
	public function getSalt() {
		return null;
	}
	
	/**
	 * Returns the username used to authenticate the user.
	 *
	 * @return string The username
	 */
	public function getUsername() {
		return $this->recruiterId;
	}
	
	/**
	 * Removes sensitive data from the user.
	 *
	 * This is important if, at any given point, sensitive information like
	 * the plain-text password is stored on this object.
	 */
	public function eraseCredentials() {
		return null;
	}
	
	public static function generate4CharacterCode($code = null) {
		if($code === null) {
			$code = base_convert(rand(0, 1679615), 10, 36);
		}
		for($i = 0; $i < 4 - strlen($code);) {
			$code = '0' . $code;
		}
		
		return $code;
	}
	
	
	public function generate4DigitCode($code = null) {
		if(empty($code)) {
			$code = rand(0, 9999);
		}
		
		$codeStr = '';
		for($n = 3; $code < pow(10, $n); $n --) {
			$codeStr .= strtoupper(chr(rand(97, 122)));
		}
		$codeStr .= $code;
		
		return $codeStr;
	}
	
	public function initiateEmployerCode() {
		if(empty($this->employerCode)) {
			$date      = new \DateTime();
			$timestamp = $date->getTimestamp();
			$tsStr     = substr(chunk_split($timestamp, 4, "-"), 0, - 1);
			$tsArray   = explode('-', $tsStr);
			
			for($i = 0; $i < count($tsArray); $i ++) {
				$part          = $tsArray[ $i ];
				$tsArray[ $i ] = $this->generate4DigitCode($part);
			}
			
			$this->employerCode = strtoupper(chr(rand(97, 122))) . strtoupper(chr(rand(97, 122))) . strtoupper(chr(rand(97, 122))) . strtoupper(chr(rand(97, 122))) . '-' . implode('-', $tsArray);
			
			
		}
		
		return $this->employerCode;
	}
	
	function __construct() {
		$this->sessions = new ArrayCollection();
	}
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="App\Entity\Interview\InterviewSession", mappedBy="recruiter", cascade={"persist", "merge"})
	 * ApiSubresource()
	 */
	protected $sessions;
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="App\Entity\Interview\InterviewSetting", mappedBy="recruiter", cascade={"persist", "merge"})
	 * ApiSubresource()
	 */
	protected $interviews;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=50)
	 * @Groups({"read_recruiter","write_recruiter"})
	 */
	protected $recruiterId;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 * @Groups({"read_recruiter","write_recruiter"})
	 */
	protected $employerCode;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=50)
	 * @Groups({"read_recruiter","write_recruiter"})
	 */
	protected $adminEmail;
//////////////////////////////////////
///
///
///
//////////////////////////////////////
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
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
	public function getInterviews(): Collection {
		return $this->interviews;
	}
	
	/**
	 * @param Collection $interviews
	 */
	public function setInterviews(Collection $interviews): void {
		$this->interviews = $interviews;
	}
	
	/**
	 * @return string
	 */
	public function getRecruiterId(): string {
		return $this->recruiterId;
	}
	
	/**
	 * @param string $recruiterId
	 */
	public function setRecruiterId(string $recruiterId): void {
		$this->recruiterId = $recruiterId;
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
	public function getAdminEmail(): string {
		return $this->adminEmail;
	}
	
	/**
	 * @param string $adminEmail
	 */
	public function setAdminEmail(string $adminEmail): void {
		$this->adminEmail = $adminEmail;
	}
}
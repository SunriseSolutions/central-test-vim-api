<?php

namespace App\Entity\Interview;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 *
 * @ORM\Entity()
 * @ORM\Table(name="interview__answer")
 */
class InterviewAnswer {
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
	 * @var InterviewSession
	 * @ORM\ManyToOne(targetEntity="InterviewSession",inversedBy="answers")
	 * @ORM\JoinColumn(name="id_session", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $session;
	
	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	protected $deadline;
	
	protected $viewedAt;
	
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
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $position = 1;
	
	protected $video;
	protected $videoThumbnail;
	protected $questionName;
	protected $questionText;

	
}
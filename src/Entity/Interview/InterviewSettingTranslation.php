<?php

namespace App\Entity\Interview;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
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
 * To translate the InterviewSetting.
 * @ApiResource(attributes={
 *     "filters"={"interview_setting__translation.search_filter",
 *     "entity__translation.search_filter", "entity__translation.boolean_filter"},
 *     "normalization_context"={"groups"={"read_interview_setting"}},
 *     "denormalization_context"={"groups"={"write_interview_setting"}}
 *
 * },
)
 *
 * @ApiFilter(OrderFilter::class, properties={"title"}, arguments={"orderParameterName"="order"})
 
 * @ORM\Entity()
 * @ORM\Table(name="interview__setting_translation")
 */
class InterviewSettingTranslation {
	
	use ORMBehaviors\Translatable\Translation;
	
	/**
	 * @var string
	 * @Groups({"read_interview_setting","write_interview_setting"})
	 */
	protected $locale;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 * @Groups({"read_interview_setting","write_interview_setting"})
	 */
	protected $title;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 * @Groups({"read_interview_setting","write_interview_setting"})
	 */
	protected $thankyouMessage;
	
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
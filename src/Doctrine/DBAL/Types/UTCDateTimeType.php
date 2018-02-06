<?php
namespace App\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

class UTCDateTimeType extends DateTimeType {
	
	static private $utc;
	
	public function getName()
	{
		return 'datetime';
	}
	
	private static function getUtc() {
		return self::$utc ? self::$utc : self::$utc = new \DateTimeZone('UTC');
	}
	
	public function convertToDatabaseValue($value, AbstractPlatform $platform) {
		if($value instanceof \DateTime) {
			$value->setTimezone(self::getUtc());
		}
		
		return parent::convertToDatabaseValue($value, $platform);
	}
	
	public function convertToPHPValue($value, AbstractPlatform $platform) {
		if(null === $value || $value instanceof \DateTime) {
			return $value;
		}
		
		$converted = \DateTime::createFromFormat(
			$platform->getDateTimeFormatString(),
			$value,
			self::getUtc()
		);
		
		if( ! $converted) {
			throw ConversionException::conversionFailedFormat(
				$value,
				$this->getName(),
				$platform->getDateTimeFormatString()
			);
		}
		
		return $converted;
	}
}
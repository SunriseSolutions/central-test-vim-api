<?php

namespace App\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

class InterviewSettingFilter extends AbstractContextAwareFilter {
	
	/**
	 * Passes a property through the filter.
	 */
	protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null/*, array $context = []*/) {
		
		$parameterName    = $queryNameGenerator->generateParameterName($property); // Generate a unique parameter name to avoid collisions with other filters
		$expr             = $queryBuilder->expr();
		$rootAlias        = $queryBuilder->getRootAliases()[0];
		$translationAlias = null;
		$translationProp = 'translations';
		$titleProp = $translationProp.'.title';
		list($translationAlias, $field, $associations) = $this->addJoinsForNestedProperty($titleProp, $rootAlias, $queryBuilder, $queryNameGenerator, $resourceClass);
		
		$transMetadata = $this->getNestedMetadata($resourceClass, $associations);
		
//		echo ' alias trans '.$translationAlias.' --- ';
		
		$joinDQL          = $queryBuilder->getDQLPart('join');
//		if(is_array($joinDQL)) {
//			$joinFromRootDQL = $joinDQL[ $rootAlias ];
//			if(is_array($joinFromRootDQL)) {
//				/** @var Join $expr */
//				foreach($joinFromRootDQL as $expr) {
//					$joinProp = str_replace($rootAlias . '.', '', $expr->getJoin());
//					if($joinProp === $translationProp) {
//						$translationAlias = $expr->getAlias();
//					}
//				}
//			}
//		}

//		$queryBuilder
//			->andWhere(sprintf(('%s.%s').' LIKE '.('CONCAT(\'%%\', :%s, \'%%\')'), $translationAlias, $field, $parameterName))
//			->setParameter($parameterName, $value);
		
		$queryBuilder
			->andWhere(
				sprintf('%1$s.%3$s LIKE :%2$s', $translationAlias, $parameterName, 'title')
				.' OR '.
				sprintf('%1$s.%3$s LIKE :%2$s', $rootAlias, $parameterName, 'creatorName')
		.' OR '.
		sprintf('%1$s.%3$s LIKE :%2$s', $rootAlias, $parameterName, 'createdAt')
			)
			->setParameter($parameterName, '%' . $value . '%');
		
//		echo ' alias trans '.$translationAlias;
//		echo $queryBuilder->getQuery()->getSQL();
//		die();
	}
	
	/**
	 * Gets the description of this filter for the given resource.
	 *
	 * Returns an array with the filter parameter names as keys and array with the following data as values:
	 *   - property: the property where the filter is applied
	 *   - type: the type of the filter
	 *   - required: if this filter is required
	 *   - strategy: the used strategy
	 *   - swagger (optional): additional parameters for the path operation,
	 *     e.g. 'swagger' => [
	 *       'description' => 'My Description',
	 *       'name' => 'My Name',
	 *       'type' => 'integer',
	 *     ]
	 * The description can contain additional data specific to a filter.
	 *
	 * @see \ApiPlatform\Core\Swagger\Serializer\DocumentationNormalizer::getFiltersParameters
	 *
	 * @param string $resourceClass
	 *
	 * @return array
	 */
	public function getDescription(string $resourceClass): array {
		$description = [];
		if( ! $this->properties) {
			return [];
		}
		$description["setting_creatorName"] = [
			'property' => 'creatorName',
			'type'     => 'string',
			'required' => false,
			'swagger'  => [
				'description' => 'Filter on properties (title,creatorName,createdAt) of an InterviewSetting!',
				'name'        => 'filter',
				'type'        => 'interview-setting',
			],
		];


//		foreach ($this->properties as $property => $strategy) {
//			$description["regexp_$property"] = [
//				'property' => $property,
//				'type' => 'string',
//				'required' => false,
//				'swagger' => [
//					'description' => 'Filter using a regex. This will appear in the Swagger documentation!',
//					'name' => 'Custom name to use in the Swagger documentation',
//					'type' => 'Will appear below the name in the Swagger documentation',
//				],
//			];
//		}
//
		return $description;
	}
}
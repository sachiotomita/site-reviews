<?php

namespace GeminiLabs\SiteReviews\Modules\Schema;

use GeminiLabs\SiteReviews\Modules\Schema\BaseType;

/**
 * A review of an item - for example, of a restaurant, movie, or store.
 * @see http://schema.org/Review
 * @method static itemReviewed( Thing|Thing[] $itemReviewed )
 * @method static reviewBody( string|string[] $reviewBody )
 * @method static reviewRating( Rating|Rating[] $reviewRating )
 */
class Review extends BaseType
{
	/**
	 * @var array
	 * @see http://schema.org/{property_name}
	 */
	protected $allowed = [
		'itemReviewed', 'reviewBody', 'reviewRating',
	];

	/**
	 * @var array
	 * @see http://schema.org/{property_name}
	 */
	protected $parents = [
		'CreativeWork',
	];
}
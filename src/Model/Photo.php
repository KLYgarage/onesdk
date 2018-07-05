<?php

namespace One\Model;

use One\Collection;
use Psr\Http\Message\UriInterface;

class Photo extends Model {
	const RATIO_SQUARE = '1:1';
	const RATIO_RECTANGLE = '2:1';
	const RATIO_HEADLINE = '3:2';
	const RATIO_VERTICAL = '9:16';
	const RATIO_COVER = 'cover';

	/**
	 * constructor
	 *
	 * @param \Psr\Http\Message\UriInterface|string $url
	 * @param string $ratio
	 * @param string $description
	 * @param string $information
	 */
	public function __construct(
		$url,
		$ratio,
		$description = '',
		$information = ''
	) {
		$url = $this->filterUriInstance($url);

		$allowedRatio = array(
			self::RATIO_SQUARE,
			self::RATIO_RECTANGLE,
			self::RATIO_HEADLINE,
			self::RATIO_VERTICAL,
			self::RATIO_COVER,
		);

		if (!in_array($ratio, $allowedRatio)) {
			throw new \Exception("ratio $ratio not allowed, allowed ratio are " . implode(', ', $allowedRatio));
		}

		$description = $this->filterStringInstance($description);
		$information = $this->filterStringInstance($information);

		$this->collection = new Collection(
			array(
				'url' => $url,
				'ratio' => $ratio,
				'description' => $description,
				'information' => $information,
			)
		);
	}
}

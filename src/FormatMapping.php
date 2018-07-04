<?php

namespace one;

use One\Model\Article;

class FormatMapping {

	/**
	 * map a single article to main attributes in Article Class
	 * @param  string $singleJsonArticle JSON response
	 * @return Article\Exception
	 */
	public function article($singleJsonArticle) {
		if ($this->jsonToArray($singleJsonArticle)) {
			$dataArticle = $this->jsonToArray($singleJsonArticle)['data'];

			$article = new Article(

				$title = $this->filterString($this->getValue('title', $dataArticle)),

				$body = $this->filterString($this->getValue('body', $dataArticle)),

				$source = $this->filterString($this->getValue('source', $dataArticle)),

				$uniqueId = $this->getValue('unique_id', $dataArticle),

				$typeId = $this->filterInteger($this->getValue('type_id', $dataArticle['type'])),

				$categoryId = $this->filterInteger($this->getValue('category_id', $dataArticle['category'])),

				$reporter = $this->getValue('reporter', $dataArticle),

				$lead = $this->filterString($this->getValue('lead', $dataArticle)),

				$tags = $this->getValue('tag_name', $dataArticle['tags']),

				$publishedAt = $this->filterDate($this->getValue('published_at', $dataArticle)),

				$identifier = $this->filterInteger($this->getValue('id', $dataArticle))
			);

			return $article;
		}

		throw new \Exception("Invalid JSON Response", 1);
	}

	/**
	 * Make sure value is integer
	 * @param  int $int
	 * @return boolean
	 */
	private function filterInteger($int) {
		if (is_int($int)) {
			return $int;
		}
		throw new \Exception("Invalid Integer", 1);
	}

	/**
	 * Make sure string is not null or empty
	 * @param   null/string $str
	 * @return string/exception
	 */
	private function filterString($str) {
		if (strlen($str) > 0 && !is_null($str)) {
			return $str;
		}
		throw new \Exception("String required", 1);
	}

	/**
	 * Make Sure Date in string with correct format state
	 *
	 * @param \DateTimeInterface|string|int|null $date
	 * @return string
	 */
	private function filterDate($date) {
		if (empty($date)) {
			$date = new \DateTime("now", new \DateTimeZone("Asia/Jakarta"));
		}

		if (is_string($date) || is_int($date)) {
			$date = new \DateTime($date);
		}

		return $this->formatDate($date);
	}

	/**
	 * Get value of array based on attributes(keys)
	 * @param  supported php variables $attribute
	 * @param  array $data
	 * @return supported php variables
	 */
	private function getValue($attribute, $data) {
		if (isset($data[$attribute])) {
			return $data[$attribute];
		}
		return null;
	}

	/**
	 * format date into required format
	 *
	 * @param \DateTimeInterface $date
	 * @return string
	 */
	private function formatDate($date) {
		return $date->format("Y-m-d H:i:s");
	}

	/**
	 * Convert JSON string to associative array
	 * @param  string $jsonResponse
	 * @return array if it is valid json, null otherwise
	 */
	public function jsonToArray($jsonResponse) {
		try {
			return json_decode($jsonResponse, true);
		} catch (\Exception $e) {
			return false;
		}
	}
}

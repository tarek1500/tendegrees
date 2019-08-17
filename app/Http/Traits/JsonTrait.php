<?php

namespace App\Http\Traits;

use Psr\Http\Message\ResponseInterface;

trait JsonTrait
{
	/**
	 * Get a short summary of the response
	 *
	 * Will return `null` if the response is not printable.
	 *
	 * @param  ResponseInterface $response
	 *
	 * @return string|null
	 */
	public function getResponseBodySummary(ResponseInterface $response)
	{
		$body = $response->getBody();

		if (!$body->isSeekable())
			return null;

		$size = $body->getSize();

		if ($size === 0)
			return null;

		$summary = $body->read($size);
		$body->rewind();

		// Matches any printable character, including unicode characters:
		// letters, marks, numbers, punctuation, spacing, and separators.
		if (preg_match('/[^\pL\pM\pN\pP\pS\pZ\n\r\t]/', $summary))
			return null;

		return json_decode($summary, true);
	}
}
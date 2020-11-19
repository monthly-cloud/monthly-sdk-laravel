<?php

namespace MonthlyCloud\Laravel\Services;

use App;
use Arr;
use MonthlyStorage;

class TranslationService
{
	/**
	 * Cached translations.
	 *
	 * @var array
	 */
	protected $translations = [];

	/**
	 * Translate key using storage dictionary.
	 *
	 * @param string $key
	 * @param string|null $locale 
	 * @return string
	 */
	public function translate($key, $locale = null)
	{
		if (empty($locale)) {
			$locale = App::getLocale();
		}

		$translations = $this->getTranslations($locale);

		if (empty($translations[$key])) {
			return $key;
		}

		return $translations[$key];
	}

	/**
	 * Get array with translations for locale.
	 *
	 * @param string|null $locale
	 * @return array
	 */
	public function getTranslations($locale = null)
	{
		if (empty($locale)) {
			$locale = App::getLocale();
		}

		if (empty($this->translations[$locale])) {
	    	// Get translations from storage.
	        $translations = MonthlyStorage::endpoint('locales')
	        	->locale($locale)
	        	->get();

	        // Turn object to array.
	        $translations = json_decode(json_encode($translations), true);

	        // Multi dimensions array to dot array.
	        $this->translations[$locale] = Arr::dot($translations);
		}

		return $this->translations[$locale];
	}
}

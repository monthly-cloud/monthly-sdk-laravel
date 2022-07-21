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
     * Get app locale.
     *
     * @return string
     */
    public function getDefaultLocale()
    {
        return App::getLocale();
    }

    /**
     * Translate key using storage dictionary.
     *
     * @param  string  $key
     * @param  string|null  $locale
     * @return string
     */
    public function translate($key, $locale = null)
    {
        if (empty($locale)) {
            $locale = $this->getDefaultLocale();
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
     * @param  string|null  $locale
     * @return array
     */
    public function getTranslations($locale = null)
    {
        if (empty($locale)) {
            $locale = $this->getDefaultLocale();
        }

        if (empty($this->translations[$locale])) {
            $this->loadTranslations($locale);
        }

        return $this->translations[$locale];
    }

    /**
     * Load translations from storage.
     *
     * Example output:
     * ['features.internet' => 'Internet', 'features.wifi' => 'WiFi']
     *
     * @param  string|null  $locale
     * @return array
     */
    public function loadTranslations($locale = null)
    {
        if (empty($locale)) {
            $locale = $this->getDefaultLocale();
        }

        // Get translations from storage.
        $translations = MonthlyStorage::endpoint('locales')
            ->locale($locale)
            ->get();

        // Turn object to array.
        $translations = json_decode(json_encode($translations), true);

        // Multi dimensions array to dot array.
        $this->translations[$locale] = Arr::dot($translations);

        return $this->translations[$locale];
    }
}

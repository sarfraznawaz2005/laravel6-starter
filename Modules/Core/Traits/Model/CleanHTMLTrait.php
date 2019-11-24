<?php

namespace Modules\Core\Traits\Model;

use Illuminate\Support\Str;

/**
 * @property array clean
 * @property array noclean
 * @property string allowedTags You can use the property to specify tags which should
 * not be stripped.
 */
trait CleanHTMLTrait
{
    /**
     * Set a given attribute on the model.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        parent::setAttribute($key, $this->tryToCleanAttributeValue($key, $value));
    }

    /**
     * Checks if given attribute should be striped from HTML. If no clean or noclean
     * are not defined, all values will be cleaned.
     *
     * @param string $key
     * @param string $value
     *
     * @return mixed
     */
    public function tryToCleanAttributeValue($key, $value)
    {
        if ($this->canRemoveHTMLFromAttribute($key, $value)) {
            if (property_exists($this, 'allowedTags') && $this->allowedTags) {
                return htmlspecialchars(strip_tags($value, $this->allowedTags));
            }

            return htmlspecialchars(strip_tags($value));
        }

        return $value;
    }

    /**
     * Checks the given attribute can be cleaned.
     *
     * @param string $key
     * @param string $value
     *
     * @return bool
     */
    public function canRemoveHTMLFromAttribute($key, $value): bool
    {
        // filter columns (optional setting)
        if (property_exists($this, 'clean') &&
            is_array($this->clean) && !in_array($key, $this->clean, true)) {
            return false;
        }

        // filter attributes that are in ignore
        if (property_exists($this, 'noclean') && in_array($key, $this->noclean, true)) {
            return false;
        }

        return is_string($value);
    }
}

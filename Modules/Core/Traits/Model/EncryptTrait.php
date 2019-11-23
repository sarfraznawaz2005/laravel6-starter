<?php

namespace Modules\Core\Traits\Model;

use Illuminate\Contracts\Encryption\DecryptException;

trait EncryptTrait
{
    /**
     * array List of attribute names which should not be saved to the database.
     *
     * protected $encrypted = ['your_field1', 'your_field2'];
     */

    /**
     * @param $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encrypted, true)) {
            try {
                $value = \Crypt::decrypt($value);
            } catch (DecryptException $e) {
                $value = $value;
                \Log::error('Value not decryptable');
            }
        }

        return $value;
    }

    public function setAttribute($key, $value, $encrypt = true)
    {
        if ($encrypt) {
            if (in_array($key, $this->encrypted, true)) {
                $value = \Crypt::encrypt($value);
            }
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * When generating an array of attributes (e.g. for the API) this will
     * decrypt the values if the attribute is found in the encrypted array.
     *
     * @return array
     */
    public function attributesToArray(): array
    {
        $attributes = parent::attributesToArray();

        foreach ($this->encrypted as $key) {
            $value = parent::getAttribute($key);
            try {
                $attributes[$key] = \Crypt::decrypt($value);
            } catch (DecryptException $e) {
                // no need to do anything here - this attribute is already in the array
            }
        }

        return $attributes;
    }
}

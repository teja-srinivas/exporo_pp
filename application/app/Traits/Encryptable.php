<?php

namespace App\Traits;

/**
 * Used to encrypt/decrypt Eloquent Model properties.
 * Any properties listed in the $encryptable array
 * will be automatically encrypted when set, and
 * decrypted when accessed, or when the model
 * is converted toJson() or toArray().
 *
 * Encryption is handled by the Crypt helper function, which
 * uses the cipher/key defined in config/app.php.
 *
 * Source:
 * https://raw.githubusercontent.com/synkyo/encryptable/9e9d3d1e893a31cbff1f9e7001ea8da527e548ea/src/Encryptable.php
 */
trait Encryptable
{

    /**
     * @param $key
     *
     * @return bool
     */
    public function encryptable($key)
    {
        return in_array($key, $this->encryptable);
    }


    /**
     * Decrypt a value.
     *
     * @param $value
     *
     * @return string
     */
    protected function decryptAttribute($value)
    {
        if ($value) {
            $value = decrypt($value);
        }

        return $value;
    }


    /**
     * Encrypt a value.
     *
     * @param $value
     *
     * @return string
     */
    protected function encryptAttribute($value)
    {
        if ($value) {
            $value = encrypt($value);
        }

        return $value;
    }


    /**
     * Extend the Eloquent method so properties present in
     * $encrypt are decrypted when directly accessed.
     *
     * @param $key  The attribute key
     *
     * @return string
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if ($this->encryptable($key)) {
            $value = $this->decryptAttribute($value);
        }

        return $value;
    }


    /**
     * Extend the Eloquent method so properties present in
     * $encrypt are encrypted whenever they are set.
     *
     * @param $key      The attribute key
     * @param $value    Attribute value to set
     *
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        if ($this->encryptable($key) && !empty($value)) {
            $value = $this->encryptAttribute($value);
        }

        return parent::setAttribute($key, $value);
    }


    /**
     * Extend the Eloquent method so properties in
     * $encrypt are decrypted when toArray()
     * or toJson() is called.
     *
     * @return mixed
     */
    public function getArrayableAttributes()
    {
        $attributes = parent::getArrayableAttributes();

        foreach ($attributes as $key => $attribute) {
            if ($this->encryptable($key)) {
                $attributes[$key] = $this->decryptAttribute($attribute);
            }
        }

        return $attributes;
    }
}

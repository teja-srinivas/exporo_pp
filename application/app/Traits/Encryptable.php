<?php

namespace App\Traits;

use Illuminate\Contracts\Encryption\DecryptException;

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
 * Original source:
 * https://raw.githubusercontent.com/synkyo/encryptable/9e9d3d1e893a31cbff1f9e7001ea8da527e548ea/src/Encryptable.php
 */
trait Encryptable
{
    /**
     * @param mixed $key
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
     * @param mixed $value
     *
     * @return string
     */
    protected function decryptAttribute($value)
    {
        return self::decrypt($value);
    }


    /**
     * Encrypt a value.
     *
     * @param mixed $value
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
     * @param mixed $key  The attribute key
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
     * @param mixed $key The attribute key
     * @param mixed $value Attribute value to set
     *
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        if (empty($value) || !$this->encryptable($key)) {
            return parent::setAttribute($key, $value);
        }

        // Set the value only if it differs from the previous one
        // as encryption always returns different results even
        // if the value stays the same (because of security).
        $previous = parent::getAttribute($key);

        if ($this->decryptAttribute($previous) === $value) {
            return $previous;
        }

        $value = $this->encryptAttribute($value);

        return parent::setAttribute($key, $value);
    }


    /**
     * Extend the Eloquent method so properties in
     * $encrypt are decrypted when toArray()
     * or toJson() is called.
     *
     * @return array
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

    /**
     * Decrypts the given value, but silently fails
     * in case we got legacy data.
     *
     * @param mixed $value
     * @return mixed
     */
    public static function decrypt($value)
    {
        static $cache;

        // Check against a safe length of an encrypted string
        // so we can just skip unencrypted data
        if ($value && strlen($value) > 160) {
            if (isset($cache[$value])) {
                return $cache[$value];
            }

            try {
                $decrypted = decrypt($value);
                $cache[$value] = $decrypted;
                $value = $decrypted;
            } catch (DecryptException $e) {
                // Ignore, as we probably have some legacy data
            }
        }

        return $value;
    }
}

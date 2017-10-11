<?php

namespace App\Core\Validator;

class Validator
{

    /**
     * @var
     */
    private $fields;
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var array
     */
    protected $messages = [
        "required"         => "The format of the %s field is invalid.",
        "alpha"            => "The field %s should be contain  only letters.",
        "alpha_dash"       => "The field %s must contain only letters, numbers and dashes.",
        "alpha_num"        => "The field %s should only contain numbers and letters.",
        "between"          => "The field %s must be between %s and %s characters long.",
        "minLength"        => "The field %s must be more than %s characters.",
        "maxLength"        => "The field %s must not be longer than %s characters long.",
        "phone"            => "The field %s does not correspond to a valid phone.",
        "email"            => "The format of the %s field is invalid."
    ];

    /**
     * Validator constructor.
     * @param array $fields
     * @param array $message
     */
    public function __construct(array $fields = [], array $message = [])
    {
        if (!empty($message)) {
            $this->message = $message;
        }
        foreach ($fields as $key => $value) {
            $this->fields[$key] = trim($value);
        }
    }

    /**
     * @param string[] ...$fields
     * @return Validator
     */
    public function required(string ...$fields): self
    {

        foreach ($fields as $key) {
            if (empty($this->errors[$key])) {
                if (array_key_exists($key, $this->fields)) {
                    if (empty($this->fields[$key])) {
                        $this->errors[$key] = sprintf($this->messages['required'], $key);
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @param string[] ...$fields
     * @return $this
     */
    public function email(string ...$fields)
    {
        foreach ($fields as $key) {
            if (empty($this->errors[$key])) {
                if (array_key_exists($key, $this->fields)) {
                    if (!filter_var($this->fields[$key], FILTER_VALIDATE_EMAIL)) {
                        $this->errors[$key] = sprintf($this->messages['email'], $key);
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @param int|null $min
     * @param int|null $max
     * @param string[] ...$fields
     * @return $this
     */
    public function length(?int $min, ?int $max = null, string ...$fields)
    {
        foreach ($fields as $key) {
            if (empty($this->errors[$key])) {
                if (array_key_exists($key, $this->fields)) {
                    if (empty($this->errors[$key])) {
                        if (!is_null($min) && !is_null($max)) {
                            if (strlen($this->fields[$key]) < $min || strlen($this->fields[$key]) > $max) {
                                $this->errors[$key] = sprintf($this->messages['between'], $key, $min, $max);
                            }
                        }
                    }

                    if (empty($this->errors[$key])) {
                        if (!is_null($min)) {
                            if (strlen($this->fields[$key]) < $min) {
                                $this->errors[$key] = sprintf($this->messages['minLength'], $min);
                            }
                        }
                    }

                    if (empty($this->errors[$key])) {
                        if (!is_null($max)) {
                            if (strlen($this->fields[$key]) > $max) {
                                $this->errors[$key] = sprintf($this->messages['maxLength'], $max);
                            }
                        }
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @param string[] ...$fields
     * @param null|string $regex
     * @return $this
     */
    public function phone(?string $regex = null, string ...$fields)
    {
        foreach ($fields as $key) {
            if (empty($this->errors[$key])) {
                if (array_key_exists($key, $this->fields)) {
                    if (!is_null($regex)) {
                        if (!preg_match($regex, $this->fields[$key])) {
                            $this->errors[$key] = sprintf($this->messages['phone'], $key);
                        }
                    } else {
                        if (!preg_match('#^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$#', $this->fields[$key])) {
                            $this->errors[$key] = sprintf($this->messages['phone'], $key);
                        }
                    }
                }
            }
        }
        return $this;
    }


    /**
     * @return bool
     */
    public function isValid() :bool
    {
        if (empty($this->errors)) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        $errors = [];
        foreach ($this->errors as $key => $message) {
            $errors['errors.' . $key] = $message;
        }
        return $errors;
    }
}

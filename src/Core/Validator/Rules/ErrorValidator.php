<?php
namespace App\Core\Validator\Rules;

class ErrorValidator
{

    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $rule;
    /**
     * @var array
     */
    private $attributes;

    /**
     * @var array
     */
    private $messages = [
        'required' => 'Le champ %s est obligatoire.',
        'empty' => 'Le champ %s ne peut être vide',
        'email' => 'Le champ $s doit être une addresse mail.',
        'phone' => 'Le champ %s doit être un numéro de télélphone.',
        'slug' => 'Le champ %s doit seulement contenir des lettres, des chiffres et des tirets.',
        'between' => 'Le texte %s doit avoir entre %d et %d caractères.',
        'minLength' => 'Le champ %s doit faire plus de %d caractères.',
        'maxLength' => 'Le champ %s ne doit pas faire plus de %d caractères.',
        'datetime' => 'Le champ %s doit être une date valide (%s)',
    ];


    /**
     * ErrorValidator constructor.
     * @param string $key
     * @param string $rule
     * @param array $attributes
     */
    public function __construct(string $key, string $rule, array $attributes = [])
    {
        $this->key = $key;
        $this->rule = $rule;
        $this->attributes = $attributes;
    }

    public function __toString()
    {
        $params = array_merge([$this->messages[$this->rule], $this->key], $this->attributes);
        return (string)call_user_func_array('sprintf', $params);
    }
}

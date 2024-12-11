<?php

namespace BotMan\BotMan\Messages\Outgoing\Actions;

use JsonSerializable;
use BotMan\BotMan\Interfaces\QuestionActionInterface;

class KeyBoard implements JsonSerializable, QuestionActionInterface
{
    /** @var string */
    protected $text; 

    /** @var string */
    protected $name;

    protected $web_app;

    /**
     * @param string $text
     *
     * @return static
     */
    public static function create($text)
    {
        return new static($text);
    }

    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }  

    /**
     * Set the button name (defaults to button text).
     *
     * @param string $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name; 
        return $this;
    }

    public function webApp($appHref)
    {
        $this->web_app = $appHref;
        return $this;
    }

    /**
     * @return array
     */
    public function get()
    {
        $name = isset($this->name) ? $this->name : $this->text;
        if ($this->web_app) {
            return ['text' => $name, 'web_app' => ['url' => $this->web_app]];
        }
        return $name;
    }

    public function toArray() {}

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}

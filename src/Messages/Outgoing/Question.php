<?php

namespace BotMan\BotMan\Messages\Outgoing;

use JsonSerializable;
use BotMan\BotMan\Interfaces\WebAccess;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Actions\KeyBoard;
use BotMan\BotMan\Interfaces\QuestionActionInterface;

class Question implements JsonSerializable, WebAccess
{
    /** @var array */
    protected $actions;

    /** @var array */
    protected $keyBoards;

    /** @var array */
    protected $oneTimeKeyboard = false;

    /** @var array */
    protected $hideKeyboard = false;

    /** @var string */
    protected $text;

    /** @var string */
    protected $callback_id;

    /** @var string */
    protected $fallback;

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
        $this->actions = [];
    }

    public function question($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Set the question fallback value.
     *
     * @param string $fallback
     * @return $this
     */
    public function fallback($fallback)
    {
        $this->fallback = $fallback;

        return $this;
    }

    /**
     * Set the callback id.
     *
     * @param string $callback_id
     * @return $this
     */
    public function callbackId($callback_id)
    {
        $this->callback_id = $callback_id;

        return $this;
    }

    public function addAction(QuestionActionInterface $action)
    {
        $this->actions[] = $action->toArray();

        return $this;
    }

    /**
     * @param \BotMan\BotMan\Messages\Outgoing\Actions\Button $button
     * @return $this
     */
    public function addButton(Button $button)
    {
        $this->actions[] = $button->toArray();

        return $this;
    }

    /**
     * @param \BotMan\BotMan\Messages\Outgoing\Actions\KeyBoard $keyBoard
     * @return $this
     */
    public function addKeyBoard(KeyBoard $keyBoard, $arr = [])
    {
        if (is_array($keyBoard)) {
            foreach ($keyBoard as $key => $value) {
                foreach ($value as $key => $value) {
                    $arr[] = $value->get();
                }
            }
            $this->keyBoards[] = $arr;
        } else {
            $this->keyBoards[] = [$keyBoard->get()];
        }

        return $this;
    }

    /**
     * @param array $keyboards
     * @return $this
     */
    public function addKeyBoards(array $keyboards, $arr = [])
    {
        foreach ($keyboards as $keyBoard) {
            if (is_array($keyBoard)) {
                foreach ($keyBoard as $key => $value) {
                    foreach ($value as $key => $value) {
                        $arr[] = $value->get();
                    }
                }
                $this->keyBoards[] = $arr;
            } else {
                $this->keyBoards[] = [$keyBoard->get()];
            }
        }

        return $this;
    }

    public function hideKeyboard()
    {
        $this->hideKeyboard = true;
        return $this;
    }

    public function needHideKeyboard()
    {
        return $this->hideKeyboard;
    }

    /**
     * @param array or string $keyboards
     * @return $this
     */
    public function addOneTimeKeyBoards($keyboards)
    {
        $this->oneTimeKeyboard = true;
        if (is_array($keyboards)) {
            $this->addKeyBoards($keyboards);
        }else{
            $this->addKeyBoard($keyboards);
        }
        return $this;
    }

    public function getOneTimeKeyBoard()
    {
        return $this->oneTimeKeyboard;
    }

    /**
     * @param array $buttons
     * @return $this
     */
    public function addButtons(array $buttons)
    {
        foreach ($buttons as $button) {
            $this->actions[] = $button->toArray();
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'text' => $this->text,
            'fallback' => $this->fallback,
            'callback_id' => $this->callback_id,
            'actions' => $this->actions,
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function getButtons()
    {
        return $this->actions;
    }

    /**
     * @return array
     */
    public function getKeyBoards()
    {
        return $this->keyBoards;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get the instance as a web accessible array.
     * This will be used within the WebDriver.
     *
     * @return array
     */
    public function toWebDriver()
    {
        return [
            'type' => (count($this->actions) > 0) ? 'actions' : 'text',
            'text' => $this->text,
            'fallback' => $this->fallback,
            'callback_id' => $this->callback_id,
            'actions' => $this->actions,
        ];
    }
}

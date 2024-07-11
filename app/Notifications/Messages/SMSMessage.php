<?php

namespace App\Notifications\Messages;

use App\Enums\SMS\SMSTypesEnum;

class SMSMessage
{
    public function __construct(
        protected string|array  $number,
        protected string        $message,
        protected ?SMSTypesEnum $type = null,
        protected ?string       $securedMessage = null
    )
    {
    }

    /**
     * @return string|array
     */
    public function getNumber(): string|array
    {
        return $this->number;
    }

    /**
     * @param string|array $number
     * @return static
     */
    public function setNumber(string|array $number): static
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return static
     */
    public function setMessage(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSecuredMessage(): ?string
    {
        return $this->securedMessage;
    }

    /**
     * @param string|null $securedMessage
     * @return static
     */
    public function setSecuredMessage(?string $securedMessage): static
    {
        $this->securedMessage = $securedMessage;
        return $this;
    }

    /**
     * @return SMSTypesEnum|null
     */
    public function getType(): ?SMSTypesEnum
    {
        return $this->type;
    }

    /**
     * @param SMSTypesEnum $type
     * @return static
     */
    public function setType(SMSTypesEnum $type): static
    {
        $this->type = $type;
        return $this;
    }
}

<?php

namespace BristolSU\Support\Action;

class ActionResponse
{

    protected $success;
    
    protected $message;

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    public function getSuccess(): ?bool
    {
        return $this->success;
    }
    
    public static function success(string $message = ''): ActionResponse
    {
        $response = new self;
        $response->setSuccess(true);
        $response->setMessage($message);
        return $response;
    }

    public static function failure(string $message = ''): ActionResponse
    {
        $response = new self;
        $response->setSuccess(false);
        $response->setMessage($message);
        return $response;
    }
    
}
<?php

namespace BristolSU\Support\Action;

class ActionResponse
{

    /**
     * @var bool
     */
    protected $success;

    /**
     * @var string
     */
    protected $message;

    /**
     * Set the response message
     * 
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * Get the response message
     * 
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Set if the action was successful
     * 
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * Get if the action was successful
     * @return bool|null
     */
    public function getSuccess(): ?bool
    {
        return $this->success;
    }

    /**
     * Create a successful response with an optional message
     * 
     * @param string $message Optional message
     * @return ActionResponse
     */
    public static function success(string $message = ''): ActionResponse
    {
        $response = new self;
        $response->setSuccess(true);
        $response->setMessage($message);
        return $response;
    }

    /**
     * Create a failed response with an optional message
     * 
     * @param string $message Optional message
     * @return ActionResponse
     */
    public static function failure(string $message = ''): ActionResponse
    {
        $response = new self;
        $response->setSuccess(false);
        $response->setMessage($message);
        return $response;
    }
    
}
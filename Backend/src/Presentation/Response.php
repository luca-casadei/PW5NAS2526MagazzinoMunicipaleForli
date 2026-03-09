<?php
declare(strict_types=1);
namespace Backend\Presentation;
class Response{
    public string $status;
    public string $message;
    public int $code;
    public mixed $body;
    //JSend Standard mettendo body come nullable dato che non si puo fare overload
    public function __construct(string $status, string $message, int $code = 200, mixed $body = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->code = $code;
        $this->body = $body;
    }
    public function get_status():string{
        return $this->status;
    }
    public function get_message():string{
        return $this->message;
    }
    public function get_body():mixed{
        return $this->body;
    }
    public function get_code():int{
        return $this->code;
    }
    public function __destruct() {}
}

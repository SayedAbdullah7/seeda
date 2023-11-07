<?php 

namespace App\Response;
use App\Repository\interfaces\responseInterface;
use Response;
use Illuminate\Http\JsonResponse;

class ApiErrorResponse implements responseInterface
{

    private int $status;
    private array $data;

    function __construct(int $st, array $data)
    {
        $this->setStatus( $st);
        $this->setData( $data);
        return $this->send();
    }
    public function setStatus(int $status) :void
    {
        $this->status= $status;
    }  

    public function setMessage(string $Message): void
    {
        $this->message= $Message;

    }

    public function setData(array $data): void
    {
        $this->data= $data;
    }
   
    public function send(): JsonResponse
    {
        return Response::json($this->data, $this->status);
    }
}
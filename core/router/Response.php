<?php

declare(strict_types=1);

namespace core\router;

class Response
{
    private $data = null;
    private $status = 200;
    private $isJson = false;

    public function __construct($data = null, $status = 200)
    {
        $this->data = $data;
        $this->status = $status;
    }

    /**
     * @param mixed $data
     * @return Response
     */
    public function json($data, $status = 200)
    {
        $this->data = $data;
        $this->status = $status;
        $this->isJson = true;

        return $this;
    }

    public function send()
    {
        http_response_code($this->status);

        if ($this->isJson) {
            header('Content-Type: application/json');
            echo json_encode($this->convertModelToArrayRecursively($this->data));
        } elseif ($this->data) {
            echo $this->data;
        }
    }

    private function convertModelToArrayRecursively($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->convertModelToArrayRecursively($value);
            }
        } elseif (is_object($data)) {
            if (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            }
        }

        return $data;
    }

    public function setStatusCode($code)
    {
        $this->status = $code;

        return $this;
    }

}

/*
return response(null, 204);
return response()->setStatusCode(204);
return response()->json($result);
 */

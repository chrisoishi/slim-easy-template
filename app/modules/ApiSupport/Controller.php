<?php

namespace App\Modules\ApiSupport;

use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Exceptions\NestedValidationException;

use Respect\Validation\Exceptions\ValidationException;

class Controller
{
    /**
     * @param Array $validator
     */
    public function checkBodyRequest(Request $request, $validations, $customMessagesError = [])
    {
        $body = $request->getParsedBody();
        $errors = "";
        foreach ($validations as $field => $validator) {
            try {
                $result = $validator->assert($body[$field]??null);
            } catch (NestedValidationException  $exception) {
                $errors .= strtoupper($field) . ": " . $this->errorToString($exception, $customMessagesError) . " | ";
            }
        }
        if ($errors != "") {
            throw new Exception($errors);
        }
        return $body;
    }

    public function errorToString(NestedValidationException $exception, $customMessages = [])
    {
        $errors = $exception->getMessages($customMessages);
        $str = "";
        foreach ($errors as $error) {
            $str .= str_replace('"', '', $error);
        }
        return $str;
    }


    public function message(Response $response, $statusCode = 200, $message, $extraInformation = [])
    {
        if ($extraInformation == null) $extraInformation = [];
        $extraInformation = array_merge(["message"=>$message], $extraInformation);
        $response->withStatus($statusCode);
        $response = $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write(
            json_encode($extraInformation)
        );
        return $response;
    }

    public function success(Response $response, $message="success", $extraInformation = [])
    {
        return $this->message($response, 200, $message, $extraInformation);
    }

    public function error(Response $response, $statusCode = 500, $message="error", $extraInformation = [])
    {
        return $this->message($response, $statusCode, $message, $extraInformation);
    }
}

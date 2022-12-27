<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2/21/2020
 * Time: 8:43 AM
 */

namespace App\Traits;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait JsonResponseTrait
{
    /**
     * Send error exception with json response
     */
    protected function sendJsonException($exception, $status = 200 )
    {
       $error = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
       ];
       if ($exception instanceof ModelNotFoundException) {
            $error['message'] = $exception->getMessage();
            $error['code'] = 404;
            $status = 404;
       }
       $response = ['success' => false];
       if( $exception instanceof ValidationException ){
            $response['message'] = $exception->getMessage();
            $validator = $exception->validator;
            $error_keys = $validator->errors()->keys();
            $error['message'] = $validator->errors()->first($error_keys[0]);
            $error['attribute'] = $error_keys[0];
            $error['code'] = 600;
        }
        $response['error'] = $error;
        return $this->sendJson( $response, $status );
    }

    /**
     * Send json success response with data
     */
    protected function sendJsonSuccess( $data, $message = null )
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
        return $this->sendJson($response);
    }

    /**
     * Send success message
     */
    protected function sendJsonMessage( $message, $data = null ){
        return $this->sendJsonSuccess( $data, $message );
    }

    /**
     * Send json error response
     */
    protected function sendJsonError( $message, $error_code = 601,  $status = 200)
    {
        $data = [
            'success' => false,
            'error' => ['message' => $message, 'code' => $error_code ],
        ];
        return $this->sendJson($data, $status);
    }

    /**
     * Send json response
     */
    protected function sendJson( $data, $status = 200 )
    {
        return Response()->json($data, $status);
    }

    /**
     * Inline validation on request input
     */
    protected function validateRequest( array $data, array $rules )
    {
       $validate = Validator::make($data, $rules);
       if( $validate->fails() ){
          throw new ValidationException( $validate );
       }
        return $validate;
    }

    /**
     * Abort unauthorised action.
     */
    protected function abort_if( $boolean, $code = 403, $message = 'This action is unauthorized.')
    {
        abort_if( $boolean, $code, $message );
    }


    /**
     * Call action of controller
     */
    public function callAction($method, $parameters)
    {
        try{
            return call_user_func_array([$this, $method], $parameters);
        }catch ( \Exception $exception ){
            return $this->sendJsonException( $exception );
        }

    }

}

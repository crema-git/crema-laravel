<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2/21/2020
 * Time: 8:01 AM
 */

namespace App\Traits;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

trait FormRequestTrait
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
     public function authorize()
     {
         return Auth::check();
     }

    /**
     * Send faild validation with json response
     */
     protected function failedValidation(Validator $validator)
     {
        $error_keys = $validator->errors()->keys();
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'The given data was invalid',
                'error' => [
                    'message' => $validator->errors()->first($error_keys[0]),
                    'code' => 600,
                    'attribute' => $error_keys[0]
                ]
            ], 200)
        );
     }

     /*public function withValidatior( $validator ){ }*/
}

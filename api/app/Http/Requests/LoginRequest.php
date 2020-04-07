<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:5',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json([
                'status' => 'bad request',
                'code' => 400,
                'message' => $errors
            ], JsonResponse::HTTP_BAD_REQUEST)
        );
    }

    public function messages()
    {
        return [
            'email.required' => 'Pole e-mail jest wymagane!',
            'password.required' => 'Pole hasło jest wymagane!',

            'email.email' => 'Pole e-mail musi zawierać poprawny adres e-mail!',
            'password.min' => 'Hasło musi posiadać przynajmniej 5 znaków!'
        ];
    }
}

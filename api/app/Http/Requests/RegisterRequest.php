<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'first_name' => 'required',
            'last_name' => 'required'
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
            'first_name.required' => 'Pole Imie jest wymagane!',
            'last_name.required' => 'Pole Nazwisko jest wymagane!',

            'email.email' => 'Pole e-mail musi zawierać poprawny adres e-mail!',
            'email.unique' => 'Podany e-mail jest już zajęty!',

            'password.min' => 'Hasło musi posiadać przynajmniej 5 znaków!'
        ];
    }
}

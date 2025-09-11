<?php

namespace App\Rules;

use Closure;

use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Validation\ValidationRule;

class GoogleReCaptcha implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public function __construct()
    {

    }


    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'  => config('services.recaptcha.secret_key'),
            'response'=> $value,
            'remoteip'=> request()->ip(),
        ]);

        $recaptcha = $response->json();

        if (!($recaptcha['success'] ?? false) || ($recaptcha['score'] ?? 0) < 0.5)
        {
            $fail('reCAPTCHA verification failed.');
        }
    }

    /**
      * Get the validation error message.
      *
      * @return string
      */

    public function message()
    {
        return 'The google recaptcha is required.';
    }
}

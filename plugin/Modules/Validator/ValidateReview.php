<?php

namespace GeminiLabs\SiteReviews\Modules\Validator;

use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Request;

class ValidateReview
{
    /**
     * @var array|false
     */
    public $errors;

    /**
     * @var string
     */
    public $message;

    /**
     * @var string
     */
    public $recaptcha;

    /**
     * @var Request
     */
    public $request;

    /**
     * @return static
     */
    public function validate(Request $request)
    {
        $this->request = $request;
        $this->request->set('ip_address', Helper::getIpAddress()); // required for Akismet and Blacklist validation
        $validators = glsr()->filterArray('validators', [ // order is intentional
            DefaultValidator::class,
            CustomValidator::class,
            PermissionValidator::class,
            HoneypotValidator::class,
            ReviewLimitsValidator::class,
            BlacklistValidator::class,
            AkismetValidator::class,
            RecaptchaValidator::class,
        ]);
        foreach ($validators as $validator) {
            if (class_exists($validator)) {
                $this->request = glsr($validator, ['request' => $this->request])->validate();
            } else {
                glsr_log()->warning("Class [$validator] not found.");
            }
        }
        $this->errors = glsr()->sessionGet($this->request->form_id.'_errors', false);
        $this->message = glsr()->sessionGet($this->request->form_id.'_message');
        $this->recaptcha = glsr()->sessionGet($this->request->form_id.'_recaptcha');
        return $this;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return false === $this->errors && 'unset' !== $this->recaptcha;
    }
}

<?php

namespace App\Actions;

use App\Libraries\Focus\FocusApi;
use App\Libraries\Focus\FocusApiException;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

class ValidatePersonAction
{
    public function execute(string $number)
    {
        if (!$this->validateFormat($number)) {
            return false;
        }

        if (!$this->validateDate($number)) {
            return false;
        }

        if (!$this->validateWithFocus($number)) {
            return false;
        }

        return true;
    }

    protected function validateFormat(string $number)
    {
        return (preg_match('/(^(19|20)[\d]{10}$)/u', $number) !== false);
    }

    protected function validateDate(string $number)
    {
        $dateOfBirth = substr($number, 0, 8);

        try {
            $date = Carbon::parse($dateOfBirth);

            if ($date->gt(today())) {
                return false;
            }
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    protected function validateWithFocus(string $number)
    {
        try {
            $response = (new FocusApi)->get_address($number);
        } catch (FocusApiException $exception) {
            $response = json_decode($exception->getMessage());

            if ($response->status == 400 && Str::contains($response->message, 'felaktigt')) {
                return false;
            }

            throw new Exception('Vi kunde tyvärr inte kontroller personnumret just nu, vänligen försök igen');
        }

        return true;
    }
}

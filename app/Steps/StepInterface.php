<?php

namespace App\Steps;

use Illuminate\Http\Request;

interface StepInterface
{
    public function view(Request $request);
    public function validateStep(Request $request);
    public function store_data($data);
    public function get_data($index);
}

<?php

namespace App\Steps\Horseinsurance\A;

use App\Libraries\Focus\FocusApi;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class A5 extends StepAbstract
{
    public $name = 'hastforsakring-a-5';
    public $progressbar = 55;

    public function view(Request $request)
    {
        $response = (new FocusApi)->get_moment(22);

        $breeds = [];

        foreach ($response as $moments) {
            foreach ($moments['falt'] as $field) {
                if ($field['namn'] == 'Ras') {
                    $breeds = $field['alternativ'];
                    break 2;
                }
            }
        }

        // Fetch session data
        $selected_breed = $this->get_data($this->name . '.breed');
        $selected_trotting = $this->get_data($this->name . '.trotting');

        $search_array_breeds = [
            'Svenskt Varmblod (SWB)',
            'Islandshäst',
            'Varmblodstravare',
            'Korsningsponny',
            'New ForestPonny'
        ];

        $breed_priority = [];
        foreach ($search_array_breeds as $search) {
            $key = array_search($search, $breeds); // $key = 2;
            if ($key !== false) {
                $breed_priority[] = $key;
            }
        }

        $horse_name = $this->get_data('hastforsakring-a-4.namn');

        return view('steps.horseinsurance.a.a5', [
            'breeds' => $breeds,
            'selected_breed' => $selected_breed,
            'selected_trotting' => $selected_trotting,
            'trotting_breeds' => $this->getTrottingBreeds(),
            'breed_priority' => $breed_priority,
            'horse_name' => $horse_name
        ]);
    }

    public function validateStep(Request $request)
    {
        $input = [
            'breed' => $request->get('breed'),
            'trotting' => $request->get('trotting') ?? null,
        ];

        $is_trotting_breed = in_array(
            $request->get('breed'),
            $this->getTrottingBreeds()
        );

        $rules = [
            'breed' => 'required',
            'trotting' => Rule::requiredIf($is_trotting_breed),
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        // Store data
        $this->store_data($input);

        $next_step = 'hastforsakring-a-7';

        // Fetch session data
        $horse_usage = $this->get_data('hastforsakring-a-1.horse_usage');
        if ($horse_usage == 2) {
            //$next_step = 'hastforsakring-a-6';
            $next_step = 'hastforsakring-a-ff-betackning';
        }

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);
    }

    protected function getTrottingBreeds()
    {
        return [
            'Varmblodstravare',
            'Kallblodstravare',
            'Angloarabiskt fullblod',
            'Engelsk Fullblod',
            'Övriga Fullblod',
        ];
    }
}

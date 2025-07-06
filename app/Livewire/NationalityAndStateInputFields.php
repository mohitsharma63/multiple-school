<?php

namespace App\Livewire;

use Livewire\Component;
use Nnjeim\World\World;

class NationalityAndStateInputFields extends Component
{
    public $nationalities;

    public $nationality;

    public $states;

    public $state;

    public $cities;

    public $city;

    protected $rules = [
        'nationality' => 'string',
        'state'       => 'string',
        'city'        => 'string',
    ];

    public function mount()
    {
        // Only show India
        $this->nationalities = collect(['India']);
        $this->nationality = 'India';

        // Initialize as empty collections
        $this->states = collect([]);
        $this->cities = collect([]);

        // Load initial states for India
        $this->loadStatesForIndia();
    }

    public function updatedNationality()
    {
        if ($this->nationality === 'India') {
            $this->loadStatesForIndia();
        }

        $this->dispatch('nationality-updated', ['nationality' => $this->nationality]);
        $this->dispatch('state-updated', ['state' => $this->state]);
        $this->dispatch('city-updated', ['city' => $this->city]);
    }

    public function updatedState()
    {
        $this->loadCitiesForState();
        $this->dispatch('state-updated', ['state' => $this->state]);
        $this->dispatch('city-updated', ['city' => $this->city]);
    }

    public function updatedCity()
    {
        $this->dispatch('city-updated', ['city' => $this->city]);
    }

    private function loadStatesForIndia()
    {
        $statesData = World::countries([
            'fields'  => 'states',
            'filters' => [
                'name' => 'India',
            ],
        ])->data->pluck('states')->first();

        $this->states = collect($statesData ?: []);

        if ($this->states->isEmpty()) {
            $this->states = collect([['name' => 'India']]);
        }

        $this->state = null;
        $this->cities = collect([]);
        $this->city = null;
    }

    private function loadCitiesForState()
    {
        if ($this->state) {
            try {
                // Find the state ID from the loaded states
                $selectedState = $this->states->firstWhere('name', $this->state);

                if ($selectedState && isset($selectedState['id'])) {
                    // Use the state ID to get cities
                    $citiesResponse = World::cities([
                        'filters' => [
                            'state_id' => $selectedState['id'],
                        ],
                    ]);

                    $this->cities = collect($citiesResponse->data ?: []);
                } else {
                    // Fallback: try with state name
                    $citiesResponse = World::cities([
                        'filters' => [
                            'state_name' => $this->state,
                        ],
                    ]);

                    $this->cities = collect($citiesResponse->data ?: []);
                }

                // If no cities found, provide a fallback
                if ($this->cities->isEmpty()) {
                    $this->cities = collect([
                        ['name' => 'Mumbai', 'id' => 1],
                        ['name' => 'Delhi', 'id' => 2],
                        ['name' => 'Bangalore', 'id' => 3],
                        ['name' => 'Chennai', 'id' => 4],
                        ['name' => 'Kolkata', 'id' => 5],
                        ['name' => 'Hyderabad', 'id' => 6],
                        ['name' => 'Pune', 'id' => 7],
                        ['name' => 'Ahmedabad', 'id' => 8],
                        ['name' => 'Jaipur', 'id' => 9],
                        ['name' => 'Lucknow', 'id' => 10],
                    ]);
                }
            } catch (\Exception $e) {
                // Fallback cities if API fails
                $this->cities = collect([
                    ['name' => 'Mumbai', 'id' => 1],
                    ['name' => 'Delhi', 'id' => 2],
                    ['name' => 'Bangalore', 'id' => 3],
                    ['name' => 'Chennai', 'id' => 4],
                    ['name' => 'Kolkata', 'id' => 5],
                ]);
            }

            $this->city = null;
        } else {
            $this->cities = collect([]);
            $this->city = null;
        }
    }

    public function loadInitialStates()
    {
        $this->loadStatesForIndia();

        $this->dispatch('nationality-updated', ['nationality' => $this->nationality]);
        $this->dispatch('state-updated', ['state' => $this->state]);
        $this->dispatch('city-updated', ['city' => $this->city]);
    }

    public function render()
    {
        return view('livewire.nationality-and-state-input-fields');
    }
}

<div class="md:flex gap-2">
    <x-select id="nationality" name="nationality" label="Nationality *" group-class="md:w-4/12"  wire:model.live="nationality">
        @foreach ($nationalities as $item)
            <option value="{{$item}}">{{$item}}</option>
        @endforeach
    </x-select>
    <x-select id="state" name="state" label="State *" group-class="md:w-4/12"  wire:init="loadInitialStates" wire:model.live="state">
        @if (isset($states) && $states->count() > 0)
            <option value="">Select a state</option>
            @foreach ($states as $item)
                <option value="{{$item['name']}}"  wire:key="{{ $loop->index }}">{{$item['name']}}</option>
            @endforeach
        @else
            <option value="" disabled>Loading states...</option>
        @endif
    </x-select>
    <x-select id="city" name="city" label="City *" group-class="md:w-4/12" wire:model.live="city">
        @if (isset($cities) && $cities->count() > 0)
            <option value="">Select a city</option>
            @foreach ($cities as $item)
                <option value="{{$item['name']}}" wire:key="{{ $loop->index }}">{{$item['name']}}</option>
            @endforeach
        @else
            <option value="" disabled>{{ $state ? 'Loading cities...' : 'Select a state first' }}</option>
        @endif
    </x-select>
</div>

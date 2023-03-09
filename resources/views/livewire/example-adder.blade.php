@if ($terms->count() > 0)

    <section>

        <h1>Add an example</h1>

        <p>
            <input type="text"
                wire:model="newTextEn" wire:keydown.enter="addExample"
                placeholder="Enter a new en example here"
            >
            @error ('newTextEn')
                <span class="error">{{ $message }}</span>
            @enderror

            <input type="text"
                wire:model.lazy="newTextBn" wire:keydown.enter="addExample"
                placeholder="Enter its bn here"
            >
            @error ('newTextBn')
                <span class="error">{{ $message }}</span>
            @enderror
        </p>



        <span>Associated with: </span>
        <div>
            <input type="text"
                wire:model="searchedAssocTerm"
            >

            <div style="border: 1px solid gray; max-height: 20vh; overflow: auto;">
                @if (sizeof($filteredAssocTerms) > 0)
                    total: {{ sizeof($filteredAssocTerms) }}
                @endif
                @foreach ($filteredAssocTerms as $id => $en)
                    @if (!in_array($id, $chosenAssocTermIds, true))
                        <button wire:click="associateTerm({{ $id }})">{{ $id }} -- {{ $en }}</button>
                    @endif
                @endforeach
            </div>

            @if (sizeof($chosenAssocTermIds) > 0)
                @foreach ($chosenAssocTermIds as $id)
                    <span>{{ $id }}</span>
                @endforeach
            @endif
        </div>
        @error ('termIds.*')
            <span class="error">{{ $message }}</span>
        @enderror



        {{-- A custom Laravel component is used for showing options
            alphabetically sorted and grouped together. --}}
        {{-- <span>Associated with: </span> --}}
        {{-- <livewire:example-assoc-term-chooser --}}
            {{-- livewire-model="termIds" --}}
            {{-- :options="$terms" --}}
            {{-- opt-id-key="id" opt-value-key="en" --}}
            {{-- ckbx-name-value="term-ids" --}}
        {{-- /> --}}
        {{-- @error ('termIds.*') --}}
            {{-- <span class="error">{{ $message }}</span> --}}
        {{-- @enderror --}}



        <p>
            <input type="text"
                wire:model="context" wire:keydown.enter="addExample"
                placeholder="Enter context here"
            >
            @error ('context')
                <span class="error">{{ $message }}</span>
            @enderror

            <input type="text"
                wire:model.lazy="subcontext" wire:keydown.enter="addExample"
                placeholder="Enter subcontext here"
            >
            @error ('subcontext')
                <span class="error">{{ $message }}</span>
            @enderror
        </p>

        <p>
            <input type="text"
                wire:model.lazy="source" wire:keydown.enter="addExample"
                placeholder="Enter source name here"
            >
            @error ('source')
                <span class="error">{{ $message }}</span>
            @enderror

            <input type="text"
                wire:model.lazy="link1" wire:keydown.enter="addExample"
                placeholder="Enter a link here"
            >
            @error ('link1')
                <span class="error">{{ $message }}</span>
            @enderror

            <input type="text"
                wire:model.lazy="link2" wire:keydown.enter="addExample"
                placeholder="Enter another link here"
            >
            @error ('link2')
                <span class="error">{{ $message }}</span>
            @enderror

            <input type="text"
                wire:model.lazy="link3" wire:keydown.enter="addExample"
                placeholder="Enter one more link here"
            >
            @error ('link3')
                <span class="error">{{ $message }}</span>
            @enderror
        </p>

        <p>
            <button wire:click="addExample" @empty($newTextEn) disabled @endempty>Add</button>
        </p>

        <p>Status: <span class="{{ $status['type'] }}">{{ $status['text'] }}<span></p>

    </section>

@endif

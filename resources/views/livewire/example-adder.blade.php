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

        {{-- A custom Laravel component is used for showing options
            alphabetically sorted and grouped together. --}}
        <span>Associated with: </span>
        <x-checkboxes-grouped-alphabetical
            livewire-model="termIds"
            :options="$terms"
            opt-id-key="id" opt-value-key="en"
            ckbx-name-value="term-ids"
        />
        @error ('termIds.*')
            <span class="error">{{ $message }}</span>
        @enderror

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

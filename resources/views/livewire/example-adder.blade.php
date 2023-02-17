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

            <input type="text" wire:model.lazy="newTextBn" placeholder="Enter its bn here">
            @error ('newTextBn')
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
            <span>Associated with: </span>
            <select wire:model="termId">
                <option value="0">Select a term</option>
                @foreach ($terms as $term)
                    <option value="{{ $term->id }}">{{ $term->en }}</option>
                @endforeach
            </select>
            @error ('termId')
                <span class="error">{{ $message }}</span>
            @enderror
        </p>

        <p>
            <button wire:click="addExample" @empty($newTextEn) disabled @endempty>Add</button>
        </p>

        <p>Status: <span class="{{ $status['type'] }}">{{ $status['text'] }}<span></p>

    </section>

@endif

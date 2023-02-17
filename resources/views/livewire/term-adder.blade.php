<section>

    <h1>Add a term</h1>

    <div>
        <input type="text" wire:model="newText" placeholder="Enter a new term here">
        @error ('newText')
            <span class="error">{{ $message }}</span>
        @enderror
        <button wire:click="addTerm" @empty($newText) disabled @endempty>Add</button>
    </div>

    <p>Status: <span class="{{ $status['type'] }}">{{ $status['text'] }}<span></p>

</section>

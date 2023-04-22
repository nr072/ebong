<section class="sentence-editor @unless ($canShowEditor) hidden @endunless">

    <button class="button" style="float: right;" wire:click="closeEditor">&times;</button>

    <p>
        <input type="text"
            wire:model.lazy="sentence.bn" wire:keydown.enter="saveUpdates"
            placeholder="Enter bn here"
        >
        @error ('sentence.bn')
            <span class="error">{{ $message }}</span>
        @enderror
    </p>

    <p>
        <input type="text"
            wire:model.lazy="sentence.context" wire:keydown.enter="saveUpdates"
            placeholder="Enter context here"
        >
        @error ('sentence.context')
            <span class="error">{{ $message }}</span>
        @enderror

        <input type="text"
            wire:model.lazy="sentence.subcontext" wire:keydown.enter="saveUpdates"
            placeholder="Enter subcontext here"
        >
        @error ('sentence.subcontext')
            <span class="error">{{ $message }}</span>
        @enderror
    </p>

    <p>
        <input type="text"
            wire:model.lazy="sentence.source" wire:keydown.enter="saveUpdates"
            placeholder="Enter source name here"
        >
        @error ('sentence.source')
            <span class="error">{{ $message }}</span>
        @enderror

        <input type="text"
            wire:model.lazy="sentence.link_1" wire:keydown.enter="saveUpdates"
            placeholder="Enter a link here"
        >
        @error ('sentence.link_1')
            <span class="error">{{ $message }}</span>
        @enderror

        <input type="text"
            wire:model.lazy="sentence.link_2" wire:keydown.enter="saveUpdates"
            placeholder="Enter another link here"
        >
        @error ('sentence.link_2')
            <span class="error">{{ $message }}</span>
        @enderror

        <input type="text"
            wire:model.lazy="sentence.link_3" wire:keydown.enter="saveUpdates"
            placeholder="Enter one more link here"
        >
        @error ('sentence.link_3')
            <span class="error">{{ $message }}</span>
        @enderror
    </p>

    <p>
        <button class="button" wire:click="saveUpdates">Update</button>
    </p>

</section>

<section class="sentence-editor @unless ($canShowEditor) hidden @endunless">

    <div>

        <button class="button" style="float: right;" wire:click="closeEditor">&times;</button>

        @isset ($sentence->en)
            <p><i>{{ $sentence->en }}</i></p>
        @endisset

        <div>
        <span>Associated with: </span>
            <input type="text" class="searchedAssocWord"
                wire:model="searchedAssocWord"
            >

            @if (sizeof($filteredAssocWords) > 0)
                <div style="border: 1px solid gray; max-height: 20vh; overflow: auto;">
                    @foreach ($filteredAssocWords as $id => $en)
                        @if (!in_array($id, $chosenAssocWordIds, true))
                            <button class="button" wire:click="associateWord({{ $id }})">{{ $id }} -- {{ $en }}</button>
                        @endif
                    @endforeach
                </div>
            @endif

            @if (sizeof($chosenAssocWordIds) > 0)
                @foreach ($chosenAssocWordIds as $id)
                    <span class="chosen-assoc-word">
                        {{ $words->find($id)->en }}
                        <button class="button" wire:click="dissociateWord({{ $id }})">&times;</button>
                    </span>
                @endforeach
            @endif
        </div>
        @error ('chosenAssocWordIds.*')
            <span class="error">{{ $message }}</span>
        @enderror

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

    </div>



    @section('js')
        <script type="text/javascript">

            "use strict";

            document.addEventListener("livewire:load", () => {

                const focusAssocWordField = () => {
                    document.querySelector(".sentence-editor .searchedAssocWord")?.focus();
                };

                Livewire.on("sentence-editor-word-associated", focusAssocWordField);
                Livewire.on("sentence-editor-word-dissociated", focusAssocWordField);

            });

        </script>
    @endsection

</section>

@if ($words->count() > 0)

    <section>

        <h1 class="text-3xl font-bold underline">Add a sentence</h1>

        <p>
            <input type="text"
                wire:model="newTextEn" wire:keydown.enter="addSentence"
                placeholder="Enter a new en sentence here"
            >
            @error ('newTextEn')
                <span class="error">{{ $message }}</span>
            @enderror

            <input type="text"
                wire:model.lazy="newTextBn" wire:keydown.enter="addSentence"
                placeholder="Enter its bn here"
            >
            @error ('newTextBn')
                <span class="error">{{ $message }}</span>
            @enderror
        </p>



        <span>Associated with: </span>
        <div>
            <input type="text" id="searchedAssocWord"
                wire:model="searchedAssocWord"
            >

            @if (sizeof($filteredAssocWords) > 0)
                <div style="border: 1px solid gray; max-height: 20vh; overflow: auto;">
                    @foreach ($filteredAssocWords as $id => $en)
                        @if (!in_array($id, $chosenAssocWordIds, true))
                            <button wire:click="associateWord({{ $id }})">{{ $id }} -- {{ $en }}</button>
                        @endif
                    @endforeach
                </div>
            @endif

            @if (sizeof($chosenAssocWordIds) > 0)
                @foreach ($chosenAssocWordIds as $id)
                    <span class="chosen-assoc-word">
                        {{ $words->find($id)->en }}
                        <button wire:click="dissociateWord({{ $id }})">&times;</button>
                    </span>
                @endforeach
            @endif
        </div>
        @error ('wordIds.*')
            <span class="error">{{ $message }}</span>
        @enderror



        <p>
            <input type="text"
                wire:model="context" wire:keydown.enter="addSentence"
                placeholder="Enter context here"
            >
            @error ('context')
                <span class="error">{{ $message }}</span>
            @enderror

            <input type="text"
                wire:model.lazy="subcontext" wire:keydown.enter="addSentence"
                placeholder="Enter subcontext here"
            >
            @error ('subcontext')
                <span class="error">{{ $message }}</span>
            @enderror
        </p>

        <p>
            <input type="text"
                wire:model.lazy="source" wire:keydown.enter="addSentence"
                placeholder="Enter source name here"
            >
            @error ('source')
                <span class="error">{{ $message }}</span>
            @enderror

            <input type="text"
                wire:model.lazy="link1" wire:keydown.enter="addSentence"
                placeholder="Enter a link here"
            >
            @error ('link1')
                <span class="error">{{ $message }}</span>
            @enderror

            <input type="text"
                wire:model.lazy="link2" wire:keydown.enter="addSentence"
                placeholder="Enter another link here"
            >
            @error ('link2')
                <span class="error">{{ $message }}</span>
            @enderror

            <input type="text"
                wire:model.lazy="link3" wire:keydown.enter="addSentence"
                placeholder="Enter one more link here"
            >
            @error ('link3')
                <span class="error">{{ $message }}</span>
            @enderror
        </p>

        <p>
            <button wire:click="addSentence" @empty($newTextEn) disabled @endempty>Add</button>
        </p>

        <p>Status: <span class="{{ $status['type'] }}">{{ $status['text'] }}<span></p>

    </section>



    <script type="text/javascript">

        "use strict";

        document.addEventListener("livewire:load", () => {

            const focusAssocWordField = () => {
                document.getElementById("searchedAssocWord")?.focus();
            };

            Livewire.on("word-associated", focusAssocWordField);
            Livewire.on("word-dissociated", focusAssocWordField);

        });

    </script>

@endif

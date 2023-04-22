@if ($words->count() > 0)

    <section>

        <h1>Add an sentence</h1>

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
        @error ('chosenAssocWordIds.*')
            <span class="error">{{ $message }}</span>
        @enderror

        @foreach ($inputs as $key => $value)
            <fieldset class="new-sentence-fields-wrap">

                <p>
                    <input type="text"
                        wire:model="inputs.{{ $key }}.en" wire:keydown.enter="addSentence"
                        placeholder="Enter a new en sentence here"
                    >
                    @error ('inputs.' . $key . '.en')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <input type="text"
                        wire:model.lazy="inputs.{{ $key }}.bn" wire:keydown.enter="addSentence"
                        placeholder="Enter its bn here"
                    >
                    @error ('inputs.' . $key . '.bn')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </p>

                <p>
                    <input type="text"
                        wire:model="inputs.{{ $key }}.context" wire:keydown.enter="addSentence"
                        placeholder="Enter context here"
                    >
                    @error ('inputs.' . $key . '.context')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <input type="text"
                        wire:model.lazy="inputs.{{ $key }}.subcontext" wire:keydown.enter="addSentence"
                        placeholder="Enter subcontext here"
                    >
                    @error ('inputs.' . $key . '.subcontext')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </p>

                <p>
                    <input type="text"
                        wire:model.lazy="inputs.{{ $key }}.source" wire:keydown.enter="addSentence"
                        placeholder="Enter source name here"
                    >
                    @error ('inputs.' . $key . '.source')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <input type="text"
                        wire:model.lazy="inputs.{{ $key }}.link1" wire:keydown.enter="addSentence"
                        placeholder="Enter a link here"
                    >
                    @error ('inputs.' . $key . '.link1')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <input type="text"
                        wire:model.lazy="inputs.{{ $key }}.link2" wire:keydown.enter="addSentence"
                        placeholder="Enter another link here"
                    >
                    @error ('inputs.' . $key . '.link2')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <input type="text"
                        wire:model.lazy="inputs.{{ $key }}.link3" wire:keydown.enter="addSentence"
                        placeholder="Enter one more link here"
                    >
                    @error ('inputs.' . $key . '.link3')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </p>

            </fieldset>
        @endforeach

        <p>
            <button wire:click="addAnotherSentence">Add another sentence</button>
        </p>

        <p>
            <button wire:click="addSentence" @empty($inputs[0]['en']) disabled @endempty>Confirm</button>
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

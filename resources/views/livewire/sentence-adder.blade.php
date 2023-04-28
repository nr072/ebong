<section class="sentence-adder">

    <h1>Add a sentence</h1>

    @if ($words->count() > 0)

        <span>Associated with: </span>
        <div>
            <input type="text" class="searched-assoc-word"
                wire:model="searchedAssocWord"
                wire:keydown.enter="addSentence"
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

        @foreach ($inputs as $key => $value)
            <fieldset class="new-sentence-fields-wrap">

                <p>
                    <input type="text"
                        wire:model.lazy="inputs.{{ $key }}.en" wire:keydown.enter="addSentence"
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
                    @if ($canShowSourceDropdown && $sources && sizeof($sources) > 0)
                        <div class="dropdown" style="margin-top: -1.05rem;">

                            <button class="button" style="float: right;"
                                wire:click="toggleSourceDropdown(0)"
                            >&times;</button>

                            @foreach ($sources as $source)
                                @if ($source)
                                    <button class="dropdown-option"
                                        wire:click="selectSource({{ $key }}, '{{ $source }}')"
                                    >{{ $source }}</button>
                                @endif
                            @endforeach

                        </div>
                    @endif
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
            <button class="button" wire:click="addAnotherSentence">Add another sentence</button>
        </p>

        <p>
            <button class="button" wire:click="addSentence" @empty($inputs[0]['en']) disabled @endempty>Confirm</button>
        </p>

        <p>Status: <span class="{{ $status['type'] }}">{{ $status['text'] }}<span></p>



        @section('js')
            <script type="text/javascript">

                "use strict";

                document.addEventListener("livewire:load", () => {

                    const focusAssocWordField = () => {
                        document.querySelector(".sentence-adder .searched-assoc-word")?.focus();
                    };

                    Livewire.on("sentence-adder-word-associated", focusAssocWordField);
                    Livewire.on("sentence-adder-word-dissociated", focusAssocWordField);

                });

            </script>
        @endsection

    @else

        <p>Status: No words found</p>

    @endif

</section>

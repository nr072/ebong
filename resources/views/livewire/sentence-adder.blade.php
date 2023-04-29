<section class="sentence-adder">

    <h1>Add a sentence</h1>

    @if ($allGroups->count() > 0)

        <span>Associate with: </span>
        <div>
            <input type="text" class="searched-group"
                wire:model="searchedGroup"
                wire:keydown.enter="createSentence"
            >

            {{-- Dropdown that shows groups matching the typed input --}}
            @if ($filteredGroups->count() > 0 && $canShowGroupDropdown)
                <div class="dropdown">

                    <button class="button" style="float: right;"
                        wire:click="toggleGroupDropdown(0)"
                    >&times;</button>

                    @foreach ($filteredGroups as $group)
                        @if (!in_array($group->id, $chosenGroupIds, true))

                            <button class="dropdown-option" wire:click="associateGroup({{ $group->id }})">
                                <b>{{ $group->title }}:</b>
                                @foreach ($group->words as $word)
                                    <span>{{ $word->en }},</span>
                                @endforeach
                            </button>

                        @endif
                    @endforeach

                </div>
            @endif

            {{-- Groups chosen for this sentence so far --}}
            @if (sizeof($chosenGroupIds) > 0)
                @foreach ($chosenGroupIds as $id)
                    <span class="chosen-assoc-group">
                        {{ $allGroups[$id] }}
                        <button class="button" wire:click="dissociateGroup({{ $id }})">&times;</button>
                    </span>
                @endforeach
            @endif
        </div>
        @error ('chosenGroupIds')
            <span class="error">{{ $message }}</span>
        @enderror

        {{-- Each sentence --}}
        @foreach ($inputs as $key => $value)
            <fieldset class="new-sentence-fields-wrap">

                <p>
                    <input type="text"
                        wire:model.lazy="inputs.{{ $key }}.en" wire:keydown.enter="createSentence"
                        placeholder="Enter a new en sentence here"
                    >
                    @error ('inputs.' . $key . '.en')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <input type="text"
                        wire:model.lazy="inputs.{{ $key }}.bn" wire:keydown.enter="createSentence"
                        placeholder="Enter its bn here"
                    >
                    @error ('inputs.' . $key . '.bn')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </p>

                <p>
                    <input type="text"
                        wire:model="inputs.{{ $key }}.context" wire:keydown.enter="createSentence"
                        placeholder="Enter context here"
                    >
                    @error ('inputs.' . $key . '.context')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <input type="text"
                        wire:model.lazy="inputs.{{ $key }}.subcontext" wire:keydown.enter="createSentence"
                        placeholder="Enter subcontext here"
                    >
                    @error ('inputs.' . $key . '.subcontext')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </p>

                <p>
                    <input type="text"
                        wire:model.lazy="inputs.{{ $key }}.source" wire:keydown.enter="createSentence"
                        placeholder="Enter source name here"
                    >
                    @if ($canShowSourceDropdown)
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
                        wire:model.lazy="inputs.{{ $key }}.link1" wire:keydown.enter="createSentence"
                        placeholder="Enter a link here"
                    >
                    @error ('inputs.' . $key . '.link1')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <input type="text"
                        wire:model.lazy="inputs.{{ $key }}.link2" wire:keydown.enter="createSentence"
                        placeholder="Enter another link here"
                    >
                    @error ('inputs.' . $key . '.link2')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <input type="text"
                        wire:model.lazy="inputs.{{ $key }}.link3" wire:keydown.enter="createSentence"
                        placeholder="Enter one more link here"
                    >
                    @error ('inputs.' . $key . '.link3')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </p>

                {{--
                    Warning: The <option> values below are hardcoded. They
                    must be updated when values in the relevant migration
                    file change.
                --}}
                <p>
                    <label>
                        Select note type:
                        <select wire:model="inputs.{{ $key }}.noteType">
                            <option value="0">Select note type</option>
                            <option value="Note">Note</option>
                            <option value="Reference">Reference</option>
                        </select>
                    </label>
                    @error ('inputs.' . $key . '.noteType')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <textarea class="disp-b mt-2" style="width: 30rem; height: 5rem; font-family: sans-serif; padding: 0.5rem;" 
                        wire:model="inputs.{{ $key }}.note"
                        placeholder="Enter note here"
                    ></textarea>
                    @error ('inputs.' . $key . '.note')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </p>

                <p>
                    <label>
                        <input type="checkbox"
                            style="vertical-align: bottom;" 
                            wire:model="inputs.{{ $key }}.needsRevision"
                        >
                        Needs revision
                    </label>
                    @error ('inputs.' . $key . '.needsRevision')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </p>

            </fieldset>
        @endforeach

        <p>
            <button class="button" wire:click="addAnotherSentence">Add another sentence</button>
        </p>

        <p>
            <button class="button" wire:click="createSentence" @empty($inputs[0]['en']) disabled @endempty>Confirm</button>
        </p>

        <p>Status: <span class="{{ $status['type'] }}">{{ $status['text'] }}<span></p>



        @section('js')
            <script type="text/javascript">

                "use strict";

                document.addEventListener("livewire:load", () => {

                    const focusGroupField = () => {
                        document.querySelector(".sentence-adder .searched-group")?.focus();
                    };

                    Livewire.on("sentence-adder-group-associated", focusGroupField);
                    Livewire.on("sentence-adder-group-dissociated", focusGroupField);

                });

            </script>
        @endsection

    @else

        <p>Status: No groups found</p>

    @endif

</section>

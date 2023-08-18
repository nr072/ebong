<section class="sentence-adder">

    <h1>Add a sentence</h1>

    @if ($allGroups->count() > 0)

        <span>Associate with: </span>
        <div>
            <input type="text" class="searched-group mr-5"
                wire:model="searchedGroup"
                wire:keydown.enter="createSentence"
            >

            {{-- Dropdown that shows groups matching the search string --}}
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

        <p class="mt-1">
            <label class="cursor-p" title="Words are suggested for association based on words from ALL sentences">
                <input type="checkbox" style="margin-left: 0;" wire:model="canEnableAutosuggestion">
                <span>Autosuggestion enabled</span>
            </label>
        </p>



        {{-- Each sentence --}}
        @foreach ($inputs as $key => $value)
            <fieldset class="new-sentence-fields-wrap flex flex-col">

                <div class="mb-1">
                    <div class="flex justify-between">

                        <label class="input-label-set w-3/4">
                            <span>Source</span>
                            <input type="text"
                                wire:model.lazy="inputs.{{ $key }}.sourceText" wire:keydown.enter="createSentence"
                            >
                            @error ('inputs.' . $key . '.sourceText')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="input-label-set w-1/6">
                            <span>Language</span>
                            <select wire:model="inputs.{{ $key }}.sourceLang">
                                {{-- TODO: Language names need to be fetched
                                     from the database and dynamically added
                                     here. --}}
                                <option value="en">en</option>
                            </select>
                            @error ('inputs.' . $key . '.sourceLang')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                    </div>

                    <div class="flex justify-between -mt-3 -mb-2">

                        <label class="input-label-set w-3/4">
                            <span>Target</span>
                            <input type="text"
                                wire:model.lazy="inputs.{{ $key }}.targetText" wire:keydown.enter="createSentence"
                            >
                            @error ('inputs.' . $key . '.targetText')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="input-label-set w-1/6">
                            <span>Language</span>
                            <select wire:model="inputs.{{ $key }}.targetLang">>
                                {{-- TODO: Language names need to be fetched
                                     from the database and dynamically added
                                     here. --}}
                                <option value="bn">bn</option>
                            </select>
                            @error ('inputs.' . $key . '.targetLang')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                    </div>
                </div>

                <div class="flex flex-row">
                    <div class="w-1/2 mr-8">

                        <div class="mb-5">
                            <label class="input-label-set">
                                <span>Context</span>
                                <input type="text"
                                    wire:model="inputs.{{ $key }}.context" wire:keydown.enter="createSentence"
                                >
                                @error ('inputs.' . $key . '.context')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="input-label-set">
                                <span>Subcontext</span>
                                <input type="text"
                                    wire:model.lazy="inputs.{{ $key }}.subcontext" wire:keydown.enter="createSentence"
                                >
                                @error ('inputs.' . $key . '.subcontext')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>

                        <div>
                            <label class="input-label-set">
                                <span>Project</span>
                                <input type="text"
                                    wire:model.lazy="inputs.{{ $key }}.project" wire:keydown.enter="createSentence"
                                >
                                @error ('inputs.' . $key . '.project')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                                @if ($canShowProjectDropdown)
                                    <span class="dropdown" style="margin-top: 1.5rem; margin-left: 6rem;">

                                        <button class="button" style="float: right;"
                                            wire:click="toggleProjectDropdown(0)"
                                        >&times;</button>

                                        @foreach ($projects as $project)
                                            @if ($project)
                                                <button class="dropdown-option"
                                                    wire:click="selectProject({{ $key }}, '{{ $project }}')"
                                                >{{ $project }}</button>
                                            @endif
                                        @endforeach

                                    </span>
                                @endif
                            </label>

                            <label class="input-label-set">
                                <span>Link 1</span>
                                <input type="text"
                                    wire:model.lazy="inputs.{{ $key }}.link1" wire:keydown.enter="createSentence"
                                >
                                @error ('inputs.' . $key . '.link1')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="input-label-set">
                                <span>Link 2</span>
                                <input type="text"
                                    wire:model.lazy="inputs.{{ $key }}.link2" wire:keydown.enter="createSentence"
                                >
                                @error ('inputs.' . $key . '.link2')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="input-label-set">
                                <span>Link 3</span>
                                <input type="text"
                                    wire:model.lazy="inputs.{{ $key }}.link3" wire:keydown.enter="createSentence"
                                >
                                @error ('inputs.' . $key . '.link3')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                    </div>

                    <div class="w-1/2 ml-8">
                        {{--
                            Warning: The <option> values below are hardcoded. They
                            must be updated when values in the relevant migration
                            file change.
                        --}}
                        <p class="my-2">
                            <label>
                                <span>Note type:</span>
                                <select wire:model="inputs.{{ $key }}.noteType">
                                    <option value="Note">Note</option>
                                    <option value="Reference">Reference</option>
                                </select>
                            </label>
                            @error ('inputs.' . $key . '.noteType')
                                <span class="error">{{ $message }}</span>
                            @enderror

                            <textarea class="disp-b mt-2 w-full h-20 p-2 font-sans" 
                                wire:model="inputs.{{ $key }}.note"
                                wire:keydown.ctrl.enter="createSentence"
                                placeholder="Enter optional notes/references here"
                            ></textarea>
                            @error ('inputs.' . $key . '.note')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </p>

                        <p class="mt-5">
                            <label class="cursor-p" title="Click to mark this sentence">
                                <input type="checkbox" class="ml-0"
                                    wire:model="inputs.{{ $key }}.needsRevision">
                                <span>Revision needed</span>
                            </label>
                            @error ('inputs.' . $key . '.needsRevision')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </p>
                    </div>
                </div>

            </fieldset>
        @endforeach

        <p class="text-center">
            <button class="button emerald" wire:click="addAnotherSentence">Add another sentence</button>
        </p>



        <p>
            <button class="button" wire:click="createSentence" @empty($inputs[0]['sourceText']) disabled @endempty>Confirm</button>
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

<section class="sentence-adder">

    <h1>Add a sentence</h1>

    @if ($allGroups->count() > 0)

        <span class="required">Associate with:</span>
        <div>
            <input type="text" class="searched-group mr-5"
                wire:model="searchedGroup"
                wire:keydown.enter="createSentence"
                placeholder="Type a word here to associate"
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
                    <span class="pill">
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
        @foreach ($inputs as $sentenceIndex => $sentence)
            <fieldset class="new-sentence-fields-wrap flex flex-col">

                <div class="mb-1">
                    <div class="flex justify-between">

                        <label class="input-label-set w-3/4">
                            <span class="input-label required">Source</span>
                            <input type="text"
                                wire:model.lazy="inputs.{{ $sentenceIndex }}.sourceText" wire:keydown.enter="createSentence"
                                required
                            >
                            @error ('inputs.'.$sentenceIndex.'.sourceText')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="input-label-set w-1/6">
                            <span class="input-label required">Language</span>
                            <select wire:model="inputs.{{ $sentenceIndex }}.sourceLang" required>
                                {{-- TODO: Language names need to be fetched
                                     from the database and dynamically added
                                     here. --}}
                                <option value="en">en</option>
                            </select>
                            @error ('inputs.'.$sentenceIndex.'.sourceLang')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                    </div>

                    @foreach ($sentence['translations'] as $senTransIndex => $senTrans)
                        <div class="flex justify-between -mt-3 -mb-2">

                            <label class="input-label-set w-3/4">
                                <span class="input-label">Target</span>
                                <input type="text"
                                    wire:model.lazy="inputs.{{ $sentenceIndex }}.translations.{{ $senTransIndex }}.targetText" wire:keydown.enter="createSentence"
                                >
                                @error ('inputs.'.$sentenceIndex.'.translations.'.$senTransIndex.'.targetText')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="input-label-set w-1/6">
                                <span class="input-label">Language</span>
                                <select wire:model="inputs.{{ $sentenceIndex }}.translations.{{ $senTransIndex }}.targetLang">>
                                    {{-- TODO: Language names need to be fetched
                                         from the database and dynamically added
                                         here. --}}
                                    <option value="bn">bn</option>
                                </select>
                                @error ('inputs.'.$sentenceIndex.'.translations.'.$senTransIndex.'.targetLang')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </label>

                        </div>
                    @endforeach

                    <button class="button ml-24"
                        wire:click="addAnotherSenTrans({{ $sentenceIndex }})"
                    >Add another translation</button>
                </div>

                <div class="flex flex-row">
                    <div class="w-1/2 mr-8">

                        <label class="input-label-set">
                            <span class="input-label">String key</span>
                            <input type="text"
                                wire:model="inputs.{{ $sentenceIndex }}.stringKey" wire:keydown.enter="createSentence"
                            >
                            @error ('inputs.'.$sentenceIndex.'.stringKey')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="input-label-set">
                            <span class="input-label">Context</span>
                            <input type="text"
                                wire:model="inputs.{{ $sentenceIndex }}.context" wire:keydown.enter="createSentence"
                            >
                            @error ('inputs.'.$sentenceIndex.'.context')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="input-label-set">
                            <span class="input-label">Subcontext</span>
                            <input type="text"
                                wire:model.lazy="inputs.{{ $sentenceIndex }}.subcontext" wire:keydown.enter="createSentence"
                            >
                            @error ('inputs.'.$sentenceIndex.'.subcontext')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="input-label-set">
                            <span class="input-label">Project</span>
                            <input type="text"
                                wire:model.lazy="inputs.{{ $sentenceIndex }}.project" wire:keydown.enter="createSentence"
                            >
                            @error ('inputs.'.$sentenceIndex.'.project')
                                <span class="error">{{ $message }}</span>
                            @enderror
                            @if ($canShowProjectDropdown)
                                <div class="dropdown" style="margin-top: 1.5rem; margin-left: 6rem;">

                                    <button class="button" style="float: right;"
                                        wire:click="toggleProjectDropdown(0)"
                                    >&times;</button>

                                    @foreach ($projects as $project)
                                        @if ($project)
                                            <button class="dropdown-option"
                                                wire:click="selectProject({{ $sentenceIndex }}, '{{ $project }}')"
                                            >{{ $project }}</button>
                                        @endif
                                    @endforeach

                                </div>
                            @endif
                        </label>

                        <label class="input-label-set">
                            <span class="input-label">Link 1</span>
                            <input type="url"
                                wire:model.lazy="inputs.{{ $sentenceIndex }}.link1" wire:keydown.enter="createSentence"
                            >
                            @error ('inputs.'.$sentenceIndex.'.link1')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="input-label-set">
                            <span class="input-label">Link 2</span>
                            <input type="url"
                                wire:model.lazy="inputs.{{ $sentenceIndex }}.link2" wire:keydown.enter="createSentence"
                            >
                            @error ('inputs.'.$sentenceIndex.'.link2')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="input-label-set">
                            <span class="input-label">Link 3</span>
                            <input type="url"
                                wire:model.lazy="inputs.{{ $sentenceIndex }}.link3" wire:keydown.enter="createSentence"
                            >
                            @error ('inputs.'.$sentenceIndex.'.link3')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>
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
                                <select wire:model="inputs.{{ $sentenceIndex }}.noteType">
                                    <option value="Note">Note</option>
                                    <option value="Reference">Reference</option>
                                </select>
                            </label>
                            @error ('inputs.'.$sentenceIndex.'.noteType')
                                <span class="error">{{ $message }}</span>
                            @enderror

                            <textarea class="disp-b mt-2 w-full h-20 p-2 font-sans" 
                                wire:model="inputs.{{ $sentenceIndex }}.note"
                                wire:keydown.ctrl.enter="createSentence"
                                placeholder="Enter optional notes/references here"
                            ></textarea>
                            @error ('inputs.'.$sentenceIndex.'.note')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </p>

                        <p class="mt-5">
                            <label class="cursor-p" title="Click to mark this sentence">
                                <input type="checkbox" class="ml-0"
                                    wire:model="inputs.{{ $sentenceIndex }}.needsRevision"
                                    required
                                >
                                <span class="required">Revision needed</span>
                            </label>
                            @error ('inputs.'.$sentenceIndex.'.needsRevision')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </p>
                    </div>
                </div>

            </fieldset>
        @endforeach

        <p class="text-center">
            <button class="button" wire:click="addAnotherSentence">Add another sentence</button>
        </p>



        <button class="button emerald block w-1/2 mt-4 mx-auto"
            wire:click="createSentence"
            @empty($inputs[0]['sourceText']) disabled @endempty
        >Confirm & add sentence(s)</button>

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

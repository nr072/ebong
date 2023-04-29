<section class="sentence-editor @unless ($canShowEditor) hidden @endunless">

    <div>

        <button class="button" style="float: right;" wire:click="closeEditor">&times;</button>

        @isset ($sentence->en)
            <p><i>{{ $sentence->en }}</i></p>
        @endisset

        <div>
        <span>Associate with: </span>
        <div>
            <input type="text" class="searched-group"
                wire:model="searchedGroup"
                wire:keydown.enter="addSentence"
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

        {{--
            Warning: The <option> values below are hardcoded. They
            must be updated when values in the relevant migration
            file change.
        --}}
        <p>
            <label>
                Select note type:
                <select wire:model="sentence.note_type">
                    <option value="Note">Note</option>
                    <option value="Reference">Reference</option>
                </select>
            </label>
            @error ('sentence.note_type')
                <span class="error">{{ $message }}</span>
            @enderror

            <textarea class="disp-b mt-2" style="width: 30rem; height: 5rem; font-family: sans-serif; padding: 0.5rem;" 
                wire:model="sentence.note"
                placeholder="Enter note here"
            ></textarea>
            @error ('sentence.note')
                <span class="error">{{ $message }}</span>
            @enderror
        </p>

        <p>
            <label>
                <input type="checkbox"
                    style="vertical-align: bottom;" 
                    wire:model="sentence.needs_revision"
                >
                Needs revision
            </label>
            @error ('sentence.needs_revision')
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

                const focusGroupField = () => {
                    document.querySelector(".sentence-editor .searched-group")?.focus();
                };

                Livewire.on("sentence-editor-group-associated", focusGroupField);
                Livewire.on("sentence-editor-group-dissociated", focusGroupField);
                Livewire.on("editor-opened", focusGroupField);

            });

        </script>
    @endsection

</section>

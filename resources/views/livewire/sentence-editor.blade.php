<section class="sentence-editor popup-wrap @unless ($canShowEditor) hidden @endunless">

    <div class="popup">

        <button class="button float-right" wire:click="closeEditor">&times;</button>

        @isset ($sentence->text)
            <p><i>{{ $sentence->text }}</i></p>
        @endisset

        <div>
        <span>Associate with: </span>
        <div>
            <input type="text" class="searched-group"
                wire:model="searchedGroup"
                wire:keydown.enter="saveUpdates"
            >

            {{-- Dropdown that shows groups matching the typed input --}}
            @if ($filteredGroups->count() > 0 && $canShowGroupDropdown)
                <div class="dropdown">

                    <button class="button float-right"
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

        @foreach ($sentence->translations as $index => $senTrans)
            <p>
                <input type="text"
                    wire:model="sentence.translations.{{ $index }}.text" wire:keydown.enter="saveUpdates"
                    placeholder="Enter translation here"
                >
                @error ('sentence.translations.'.$index. '.text')
                    <span class="error">{{ $message }}</span>
                @enderror
            </p>
        @endforeach

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

            <input type="text"
                wire:model.lazy="sentence.project" wire:keydown.enter="saveUpdates"
                placeholder="Enter project name here"
            >
            @error ('sentence.project')
                <span class="error">{{ $message }}</span>
            @enderror
        </p>

        <p>
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

            <textarea class="disp-b mt-2 w-3/4 max-w-lg h-20 p-2 font-sans"
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

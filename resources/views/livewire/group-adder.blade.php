<section class="group-adder half-width-section">

    <h1>Add a group</h1>

    @if ($grouplessWords->count() > 0)

        <label class="input-label-set">
            <span class="input-label required">Title</span>
            <input type="text"
                wire:model="title" wire:keydown.enter="createGroup"
                placeholder="Enter group title here"
                required
            >
        </label>
        @error ('title')
            <span class="error">{{ $message }}</span>
        @enderror

        <label class="input-label-set">
            <span class="input-label required">Word(s)</span>
            <input type="text" class="searched-word"
                wire:model="searchedWord" wire:keydown.enter="createGroup"
                placeholder="Type to search words"
                required
            >
        </label>
        @error ('chosenWordIds')
            <span class="error">{{ $message }}</span>
        @enderror
        @if (sizeof($filteredWords) > 0)
            <div class="dropdown" style="margin-left: 10rem; margin-top: -0.5em;">
                @foreach ($filteredWords as $word)
                    @if (!in_array($word->id, $chosenWordIds, true))
                        <button class="dropdown-option" wire:click="addWordToGroup({{ $word->id }})">
                            {{ $word->en }}
                            @if ($word->pos)
                                <small><i>{{ $word->pos->short }}</i></small>
                            @endif
                        </button>
                    @endif
                @endforeach
            </div>
        @endif

        @if (sizeof($chosenWordIds) > 0)
            <div class="mt-4">Already added words:
            @foreach ($chosenWordIds as $id)
                <span class="pill">
                    {{ $grouplessWords->find($id)->en }}
                    <button class="button"
                        wire:click="removeWordFromGroup({{ $id }})"
                    >&times;</button>
                </span>
            @endforeach
            </div>
        @endif

        <button class="button emerald block w-1/2 mt-4 mx-auto"
            wire:click="createGroup"
            @empty($title) disabled @endempty
        >Confirm</button>

        <p>Status: <span class="{{ $status['type'] }}">{{ $status['text'] }}</span></p>



        @section('js')
            <script type="text/javascript">

                "use strict";

                document.addEventListener("livewire:load", () => {

                    const focusWordField = () => {
                        document.querySelector(".searched-word")?.focus();
                    };

                    Livewire.on("word-added-to-group", focusWordField);
                    Livewire.on("word-removed-from-group", focusWordField);

                });

            </script>
        @endsection

    @else

        <p>No suitable words found. Create some first.</p>

    @endif

</section>

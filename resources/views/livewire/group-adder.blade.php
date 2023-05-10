<section class="half-width-section">

    <h1>Add a group</h1>

    @if ($grouplessWords->count() > 0)

        <p>
            <label>
                Group title:
                <input type="text"
                    wire:model="title" wire:keydown.enter="createGroup"
                    placeholder="Enter group title here"
                >
            </label>
            @error ('title')
                <span class="error">{{ $message }}</span>
            @enderror
        </p>

        <p>
            <label>
                Add an existing word:
                <input type="text" class="searched-word"
                    wire:model="searchedWord" wire:keydown.enter="createGroup"
                    placeholder="Type to search words"
                >
            </label>
            @error ('chosenWordIds')
                <span class="error">{{ $message }}</span>
            @enderror
        </p>
        @if (sizeof($filteredWords) > 0)
            <div class="dropdown" style="margin-left: 10rem;">
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
            <p class="mt-0">
                <span style="display: block;">Already added words:</span>
                @foreach ($chosenWordIds as $id)
                    <span class="chosen-assoc-word">
                        {{ $grouplessWords->find($id)->en }}
                        <button class="button" wire:click="removeWordFromGroup({{ $id }})">&times;</button>
                    </span>
                @endforeach
            </p>
        @endif

        <p>
            <button class="button"
                wire:click="createGroup"
                @empty($title) disabled @endempty
            >Confirm</button>
        </p>

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

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
            @if (sizeof($filteredWords) > 0)
                <div style="border: 1px solid gray; max-height: 20vh; overflow: auto; margin-bottom: 0.5rem;">
                    @foreach ($filteredWords as $id => $en)
                        @if (!in_array($id, $chosenWordIds, true))
                            <button class="button" wire:click="addWordToGroup({{ $id }})">{{ $en }}</button>
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
            @error ('chosenWordIds')
                <span class="error">{{ $message }}</span>
            @enderror
        </p>

        <button class="button"
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

        <p>Status: No suitable words found. Create some first.</p>

    @endif

</section>

<section class="cluster-adder half-width-section">

    <h1>Add a cluster</h1>

    @if ($clusterlessWords->count() > 0)

        <label class="input-label-set">
            <span class="input-label required">Name</span>
            <input type="text"
                wire:model="name" wire:keydown.enter="createCluster"
                placeholder="Enter cluster name here"
                required
            >
            @if ($name)
                <button class="button input-clear-btn"
                    wire:click="resetInput('name')"
                >&times;</button>
            @endif
        </label>
        @error ('name')
            <span class="error">{{ $message }}</span>
        @enderror

        <label class="input-label-set">
            <span class="input-label required">Word(s)</span>
            <input type="text" class="searched-word"
                wire:model="searchedWord" wire:keydown.enter="createCluster"
                placeholder="Type to search words"
                required
            >
            @if ($searchedWord)
                <button class="button input-clear-btn"
                    wire:click="resetInput('searchedWord')"
                >&times;</button>
            @endif
        </label>
        @error ('chosenWordIds')
            <span class="error">{{ $message }}</span>
        @enderror
        @if (sizeof($filteredWords) > 0)
            <div class="dropdown" style="margin-left: 10rem; margin-top: -0.5em;">
                @foreach ($filteredWords as $word)
                    @if (!in_array($word->id, $chosenWordIds, true))
                        <button class="dropdown-option" wire:click="addWordToCluster({{ $word->id }})">
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
                    {{ $clusterlessWords->find($id)->en }}
                    <button class="button"
                        wire:click="removeWordFromCluster({{ $id }})"
                    >&times;</button>
                </span>
            @endforeach
            </div>
        @endif



        {{-- A list of words that don't belong to any clusters yet. --}}
        @if ($clusterlessWords->count() > 0)
            <div class="my-8">
                <span>Available words:</span>
                <ul class="clusterless-word-list">
                    @foreach ($clusterlessWords as $word)
                        <li>
                            {{ $word->en }}
                            @if ($word->pos)
                                <small><i>{{ $word->pos->short }}</i></small>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif



        <button class="button emerald block w-1/2 mt-4 mx-auto"
            wire:click="createCluster"
        >Confirm</button>

        <p>Status: <span class="{{ $status['type'] }}">{{ $status['text'] }}</span></p>



        @section('js')
            <script type="text/javascript">

                "use strict";

                document.addEventListener("livewire:load", () => {

                    const focusWordField = () => {
                        document.querySelector(".searched-word")?.focus();
                    };

                    Livewire.on("word-added-to-cluster", focusWordField);
                    Livewire.on("word-removed-from-cluster", focusWordField);

                });

            </script>
        @endsection

    @else

        <p>No suitable words found. Create some first.</p>

    @endif

</section>

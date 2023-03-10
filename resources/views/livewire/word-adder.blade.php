<section class="word-adder">

    <div style="display: inline-block; width: 49%; vertical-align: top;">

        <h1 class="text-3xl font-bold underline">Add a word</h1>

        <div>
            <input type="text"
                wire:model="newText" wire:keydown.enter="addWord"
                placeholder="Enter a new word here"
            >
            @error ('newText')
                <span class="error">{{ $message }}</span>
            @enderror

            <button wire:click="addWord" @empty($newText) disabled @endempty>Add</button>
        </div>

        @if ($matchedForNew->count() > 0)
            <div style="padding-top: 0.5em;">
                <span>Matches found:</span>
                <ul class="matched-list">
                    @foreach ($matchedForNew as $id => $en)
                        <li>{{ $en }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p>Status: <span class="{{ $status['type'] }}">{{ $status['text'] }}</span></p>

    </div>



    <div style="display: inline-block; width: 49%;">

        <h1>Words</h1>

        <input type="text" wire:model="searchedEn" placeholder="Type to search en words">
        @if ($searchedEn)
            <button wire:click="resetSearchedEn">&times;</button>
        @endif

        @if ($words->count() < 1)
            <p>Status: No words found</p>
        @else
            <ul>
                @foreach ($words as $word)
                    <li class="single-word">
                        <span>{{ $word->en }}</span>
                    </li>
                @endforeach
            </ul>
            {{ $words->links() }}
        @endif

    </div>

</section>

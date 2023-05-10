<section class="word-adder">

    <div style="display: inline-block; width: 49%; vertical-align: top;">

        <h1 class="text-3xl font-bold underline">Add a word</h1>

        <div>
            <p class="mb-2">
                <input type="text"
                    wire:model="newWordEn" wire:keydown.enter="addWord"
                    placeholder="Enter a new word here"
                >
                @error ('newWordEn')
                    <span class="error">{{ $message }}</span>
                @enderror
            </p>

            <p class="mt-2">
                <select wire:model="newWordPos">
                    <option value="0">Select POS</option>
                    @foreach ($poses as $id => $en)
                        <option value="{{ $id }}">{{ $en }}</option>
                    @endforeach
                </select>
                @error ('newWordPos')
                    <span class="error">{{ $message }}</span>
                @enderror
            </p>

            <p>
                <label>
                    Select a group:
                    <select wire:model="newWordGroup">
                        <option value="0">Select group</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}">
                                {{ $group->title }}
                                ({{ $group->words->count() }} word{{ $group->words->count() > 1 ? 's' : '' }})
                            </option>
                        @endforeach
                    </select>
                </label>
                @error ('newWordGroup')
                    <span class="error">{{ $message }}</span>
                @enderror
            </p>

            <button class="button"
                wire:click="addWord"
                @empty($newWordEn) disabled @endempty
            >Add</button>
        </div>

        {{-- A list of existing words that partially match the typed input --}}
        @if ($wordsMatchingSearched->count() > 0)
            <div style="padding-top: 0.5em;">
                <span>Matches found among existing words:</span>
                <ul class="matched-list">
                    @foreach ($wordsMatchingSearched as $word)
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

        <p>Status: <span class="{{ $status['type'] }}">{{ $status['text'] }}</span></p>

    </div>



    <div style="display: inline-block; width: 49%;">

        <h1>Words</h1>

        <input type="text" wire:model="searchedEn" placeholder="Type to search en words">
        @if ($searchedEn)
            <button class="button" wire:click="resetSearchedEn">&times;</button>
        @endif

        @if ($words->count() < 1)
            <p>Status: No words found</p>
        @else
            <ul>
                @foreach ($words as $word)
                    <li class="single-word">
                        <span>
                            @if ($word->pos && $word->pos->en === 'proper noun')
                                {{ ucwords($word->en) }}
                            @else
                                {{ $word->en }}
                            @endif
                            @if ($word->pos)
                                <small><i>{{ $word->pos->short }}</i></small>
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
            {{ $words->links() }}
        @endif

    </div>

</section>

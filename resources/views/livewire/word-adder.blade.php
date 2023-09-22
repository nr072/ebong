<section class="word-adder">

    <div style="display: inline-block; width: 40%; vertical-align: top;">

        <h1>Add a word</h1>

        <div>
            <label class="input-label-set">
                <span>Word</span>
                <input type="text"
                    wire:model="newWordEn" wire:keydown.enter="addWord"
                    placeholder="Enter a new word here"
                >
            </label>
            @error ('newWordEn')
                <span class="error">{{ $message }}</span>
            @enderror

            <label class="input-label-set">
                <span>POS</span>
                <select wire:model="newWordPos">
                    <option value="0">Select part of speech</option>
                    @foreach ($poses as $id => $en)
                        <option value="{{ $id }}">{{ $en }}</option>
                    @endforeach
                </select>
            </label>
            @error ('newWordPos')
                <span class="error">{{ $message }}</span>
            @enderror

            <label class="input-label-set">
                <span>Group</span>
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

            <button class="button block w-1/2 mt-4 mx-auto"
                wire:click="addWord"
                @empty($newWordEn) disabled @endempty
            >Confirm</button>
        </div>

        {{-- A list of existing words that partially match the typed input --}}
        @if ($wordsMatchingSearched->count() > 0)
            <div class="mt-8">
                <span>Matches found among existing words:</span>
                <ul class="matched-list">
                    @foreach ($wordsMatchingSearched as $word)
                        <li>
                            {{ $word->en }}
                            @if ($word->pos)
                                <small><i>{{ $word->pos->short }}</i></small>
                            @endif
                            @if ($word->group)
                                (Group: {{ $word->group->title }})
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p>Status: <span class="{{ $status['type'] }}">{{ $status['text'] }}</span></p>

    </div>



    <div style="display: inline-block; width: 40%;">

        <h1>Words</h1>

        <label class="input-label-set">
            <span>Word</span>
            <input type="search" wire:model="searchedEn" placeholder="Type to search en words">
            @if ($searchedEn)
                <button class="button" wire:click="resetSearchedEn">&times;</button>
            @endif
        </label>

        @if ($words->count() < 1)
            <p>Status: No words found</p>
        @else
            <ul class="mt-4">
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
                            @if ($word->group)
                                (Group: {{ $word->group->title }})
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
            {{ $words->links() }}
        @endif

    </div>

</section>

<section class="popup-wrap @unless ($canShowEditor) hidden @endunless">

    <div class="popup" style="padding: 2rem">

        <button class="button" style="margin: -1rem -1rem 0 0; float: right;"
            wire:click="closeEditor"
        >&times;</button>

        @if ($group)

            <h1>{{ $group->title }}</h1>

            @if ($group->words->count() > 0)
                <p class="mb-1">Words already associated to this group:</p>
                <ul class="mt-0">
                    @foreach ($group->words as $word)
                        <li>{{ $word->en }} <i>{{ $word->pos->short }}</i></li>
                    @endforeach
                </ul>
            @endif

            <p class="mb-1">Add more words:</p>

            <div>
                <p class="mt-1 mb-1">
                    <input type="text" wire:model="searched">
                    <button class="button" wire:click="resetSearched()">&times;</button>
                </p>

                {{-- Dropdown that shows words matching the search string --}}
                @if ($words->count() > 0 && $canShowWordDropdown)
                    <div class="dropdown">
                        <button class="button" style="float: right;"
                            wire:click="toggleWordDropdown(0)"
                        >&times;</button>

                        @foreach ($words as $word)
                            <button class="dropdown-option"
                                wire:click="associateWord({{ $word->id }})"
                            >{{ $word->en }} <i>{{ $word->pos->short }}</i></button>
                        @endforeach
                    </div>
                @endif

                {{--
                    Shows the words currently associated. Includes already
                    associated words and words that were just added by the
                    user (but not confirmed/updated yet).
                --}}
                @if (sizeof($chosenWords) > 0)
                    <p>
                        <span>Curently associated:</span>
                        @foreach ($chosenWords as $word)
                            <span class="chosen-assoc-word">
                                {{ $word->en }} <i>{{ $word->pos->short }}</i>
                                <button class="button"
                                    wire:click="dissociateWord({{ $word->id }})"
                                >&times;</button>
                            </span>
                        @endforeach
                    </p>
                @endif
                @error ('chosenWordIds')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <p>
                <button class="button" wire:click="saveUpdates">Update</button>
            </p>

        @endif

    </div>

</section>

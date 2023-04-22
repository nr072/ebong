<section>

    <h1>Sentences</h1>

    <table class="alt-rows">
        <thead>
            <tr>
                <th class="cell-en">en</th>
                <th class="cell-bn">bn</th>

                <th class="cell-context">Context</th>

                <th class="cell-word">Words</th>

                <th class="cell-source">Source</th>

                <th class="cell-links">Links</th>
            </tr>
        </thead>
        <tbody>

            <tr class="search-fields-row">
                <td>
                    <div>
                        <input type="text"
                            wire:model="searchedEn"
                            placeholder="Type to search"
                        >
                        @if ($searchedEn)
                            <button class="button"
                                wire:click="resetSearched('en')"
                                title="Click to clear searched string"
                            >&times;</button>
                        @endif
                    </div>
                </td>
                <td>
                    <div>
                        <input type="text"
                            wire:model="searchedBn"
                            placeholder="Type to search"
                        >
                        @if ($searchedBn)
                            <button class="button"
                                wire:click="resetSearched('bn')"
                                title="Click to clear searched string"
                            >&times;</button>
                        @endif
                    </div>
                </td>

                <td>
                    <div>
                        <input type="text"
                            wire:model="searchedContext"
                            placeholder="Type to search"
                        >
                        @if ($searchedContext)
                            <button class="button"
                                wire:click="resetSearched('context')"
                                title="Click to clear searched string"
                            >&times;</button>
                        @endif
                    </div>
                </td>

                <td>
                    <div>
                        <input type="text"
                            wire:model="searchedWord"
                            placeholder="Type to search"
                        >
                        @if ($searchedWord)
                            <button class="button"
                                wire:click="resetSearched('word')"
                                title="Click to clear searched string"
                            >&times;</button>
                        @endif
                    </div>
                </td>

                <td>
                    <div>
                        <input type="text"
                            wire:model="searchedSource"
                            placeholder="Type to search"
                        >
                        @if ($searchedSource)
                            <button class="button"
                                wire:click="resetSearched('source')"
                                title="Click to clear searched string"
                            >&times;</button>
                        @endif
                    </div>
                </td>

                <td></td>
            </tr>

            @if ($sentences->count() > 0)

                @foreach ($sentences as $sentence)
                    <tr>
                        <td class="cell-en">{{ $sentence->en }}</td>
                        <td class="cell-bn">{{ $sentence->bn }}</td>

                        <td class="cell-context">
                            @if ($sentence->context)
                                <div>{{ $sentence->context }}</div>
                            @endif
                            @if ($sentence->subcontext)
                                <div>{{ $sentence->subcontext }}</div>
                            @endif
                        </td>

                        <td class="cell-word">
                            @foreach ($sentence->words as $word)
                                <span>{{ $word->en }}</span>
                            @endforeach
                        </td>

                        <td class="cell-source">{{ $sentence->source }}</td>

                        <td class="cell-links">
                            <span>
                                @if ($sentence->link_1)
                                    <a href="{{ $sentence->link_1 }}">#1</a>
                                @endif
                            </span>
                            <span>
                                @if ($sentence->link_2)
                                    <a href="{{ $sentence->link_2 }}">#2</a>
                                @endif
                            </span>
                            <span>
                                @if ($sentence->link_3)
                                    <a href="{{ $sentence->link_3 }}">#3</a>
                                @endif
                            </span>
                        </td>
                    </tr>
                @endforeach

            @endif

        </tbody>
    </table>

    {{ $sentences->links() }}



    @if ($sentences->count() < 1)
        <p>Status: No sentences found</p>
    @endif

</section>

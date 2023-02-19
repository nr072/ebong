<section>

    <h1>Examples</h1>

    <table class="alt-rows">
        <thead>
            <tr>
                <th class="cell-en">en</th>
                <th class="cell-bn">bn</th>

                <th class="cell-context">Context</th>

                <th class="cell-term">Terms</th>

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
                            <button wire:click="resetSearched('en')"
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
                            <button wire:click="resetSearched('bn')"
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
                            <button wire:click="resetSearched('context')"
                                title="Click to clear searched string"
                            >&times;</button>
                        @endif
                    </div>
                </td>

                <td>
                    <div>
                        <input type="text"
                            wire:model="searchedTerm"
                            placeholder="Type to search"
                        >
                        @if ($searchedTerm)
                            <button wire:click="resetSearched('term')"
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
                            <button wire:click="resetSearched('source')"
                                title="Click to clear searched string"
                            >&times;</button>
                        @endif
                    </div>
                </td>

                <td></td>
            </tr>

            @if ($examples->count() > 0)

                @foreach ($examples as $example)
                    <tr>
                        <td class="cell-en">{{ $example->en }}</td>
                        <td class="cell-bn">{{ $example->bn }}</td>

                        <td class="cell-context">
                            @if ($example->context)
                                <div>{{ $example->context }}</div>
                            @endif
                            @if ($example->subcontext)
                                <div>{{ $example->subcontext }}</div>
                            @endif
                        </td>

                        <td class="cell-term">
                            @foreach ($example->terms as $term)
                                <span>{{ $term->en }}</span>
                            @endforeach
                        </td>

                        <td class="cell-source">{{ $example->source }}</td>

                        <td class="cell-links">
                            <span>
                                @if ($example->link_1)
                                    <a href="{{ $example->link_1 }}">#1</a>
                                @endif
                            </span>
                            <span>
                                @if ($example->link_2)
                                    <a href="{{ $example->link_2 }}">#2</a>
                                @endif
                            </span>
                            <span>
                                @if ($example->link_3)
                                    <a href="{{ $example->link_3 }}">#3</a>
                                @endif
                            </span>
                        </td>
                    </tr>
                @endforeach

            @endif

        </tbody>
    </table>

    {{ $examples->links() }}



    @if ($examples->count() < 1)
        <p>Status: No examples found</p>
    @endif

</section>

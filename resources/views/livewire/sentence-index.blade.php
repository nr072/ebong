<section>

    <h1>Sentences</h1>

    <table class="alt-rows">
        <thead>
            <tr>
                <th></th>
                <th>Text</th>
                <th class="cell-group">Groups</th>
                <th class="cell-source">Source</th>
            </tr>
        </thead>
        <tbody>

            <tr class="search-fields-row">
                <td></td>
                <td class="cell-text">
                    <div>
                        <input type="text"
                            wire:model="searchedEn"
                            placeholder="Type to search en"
                        >
                        @if ($searchedEn)
                            <button class="button"
                                wire:click="resetSearched('en')"
                                title="Click to clear searched string"
                            >&times;</button>
                        @endif
                    </div>
                    <div>
                        <input type="text"
                            wire:model="searchedBn"
                            placeholder="Type to search bn"
                        >
                        @if ($searchedBn)
                            <button class="button"
                                wire:click="resetSearched('bn')"
                                title="Click to clear searched string"
                            >&times;</button>
                        @endif
                    </div>
                    <div>
                        <input type="text"
                            wire:model="searchedContext"
                            placeholder="Type to search context"
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
                            wire:model="searchedGroup"
                            placeholder="Type to search"
                        >
                        @if ($searchedGroup)
                            <button class="button"
                                wire:click="resetSearched('group')"
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
            </tr>

            @if ($sentences->count() > 0)

                @foreach ($sentences as $sentence)
                    <tr>
                        <td class="cell-buttons">
                            <button class="button"
                                @if ($isEditing) disabled @endif
                                wire:click="editSentence({{ $sentence->id }})"
                            >Edit</button>
                        </td>

                        <td>

                            <div>{{ $sentence->en }}</div>

                            <div style="font-size: 0.9em;">{{ $sentence->bn }}</div>

                            @if ($sentence->context)
                                <div class="text-indented text-gray">
                                    <small>Context:</small> {{ $sentence->context }}
                                </div>
                            @endif
                            @if ($sentence->subcontext)
                                <div class="text-indented text-gray">
                                    <small>Subcontext:</small> {{ $sentence->subcontext }}
                                </div>
                            @endif

                            @if ($sentence->link_1 || $sentence->link_2 || $sentence->link_3)
                                <div class="text-indented text-gray">
                                    <small>Links: </small>
                                    <span>
                                        @if ($sentence->link_1)
                                            <a href="{{ $sentence->link_1 }}" title="{{ $sentence->link_1 }}">URL</a>
                                        @endif
                                    </span>
                                    <span>
                                        @if ($sentence->link_2)
                                            <a href="{{ $sentence->link_2 }}" title="{{ $sentence->link_2 }}">URL</a>
                                        @endif
                                    </span>
                                    <span>
                                        @if ($sentence->link_3)
                                            <a href="{{ $sentence->link_3 }}" title="{{ $sentence->link_3 }}">URL</a>
                                        @endif
                                    </span>
                                </div>
                            @endif

                            @if ($sentence->note)
                                <div class="text-indented text-gray">
                                    <small>{{ $sentence->note_type }}:</small>
                                    <span>{{ $sentence->note }}</span>
                                </div>
                            @endif

                            @if ($sentence->needs_revision)
                                <div class="text-indented text-gray">
                                    <small><i style="color: rgb(202 127 50);">This sentence needs revision</i></small>
                                </div>
                            @endif

                        </td>

                        <td class="cell-group">
                            @foreach ($sentence->groups as $group)
                                <div>{{ $group->title }}</div>
                            @endforeach
                        </td>

                        <td class="cell-source">{{ $sentence->source }}</td>
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

<section class="sentence-index borderless">

    {{-- <h1>Sentences</h1> --}}

    <div class="search-fields-wrap flex justify-evenly mt-5">
        <div>
            <div>
                <input type="text"
                    wire:model="searchedEn"
                    class="w-96" 
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
                    class="w-96" 
                    placeholder="Type to search bn"
                >
                @if ($searchedBn)
                    <button class="button"
                        wire:click="resetSearched('bn')"
                        title="Click to clear searched string"
                    >&times;</button>
                @endif
            </div>
            <div class="mt-1">
                <input type="text"
                    wire:model="searchedContext"
                    class="w-96" 
                    placeholder="Type to search context"
                >
                @if ($searchedContext)
                    <button class="button"
                        wire:click="resetSearched('context')"
                        title="Click to clear searched string"
                    >&times;</button>
                @endif
            </div>
        </div>

        <div>
            <div>
                <input type="text"
                    wire:model="searchedGroup"
                    placeholder="Type to search associated group"
                >
                @if ($searchedGroup)
                    <button class="button"
                        wire:click="resetSearched('group')"
                        title="Click to clear searched string"
                    >&times;</button>
                @endif
            </div>
            <div>
                <input type="text"
                    wire:model="searchedSource"
                    placeholder="Type to search source"
                >
                @if ($searchedSource)
                    <button class="button"
                        wire:click="resetSearched('source')"
                        title="Click to clear searched string"
                    >&times;</button>
                @endif
            </div>
        </div>
    </div>



    @if ($sentences->count() < 1)

        <p>Status: No sentences found</p>

    @else

        <table class="my-5">
            <thead>
                <tr>
                    <th class="cell-flags"></th>
                    <th class="cell-group">Groups</th>
                    <th>Text</th>
                    <th class="cell-source">Source</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>

                @if ($sentences->count() > 0)

                    @foreach ($sentences as $sentence)
                        <tr>
                            <td class="cell-flags">
                                @if ($sentence->needs_revision)
                                    <span class="flag warning" title="Needs revision"></span>
                                    <span class="flag warning" title="Needs revision"></span>
                                    <span class="flag error" title="Needs revision"></span>
                                    <span class="flag info" title="Needs revision"></span>
                                    <span class="flag error" title="Needs revision"></span>
                                    <span class="flag error" title="Needs revision"></span>
                                @endif
                            </td>

                            <td class="cell-group">
                                @foreach ($sentence->groups as $group)
                                    <div>{{ $group->title }}</div>
                                @endforeach
                            </td>

                            <td>

                                <div>{{ $sentence->en }}</div>

                                <div class="bn-text">{{ $sentence->bn }}</div>

                                {{-- @if ($sentence->context)
                                    <div class="text-indented text-gray">
                                        <small>Context:</small> {{ $sentence->context }}
                                    </div>
                                @endif
                                @if ($sentence->subcontext)
                                    <div class="text-indented text-gray">
                                        <small>Subcontext:</small> {{ $sentence->subcontext }}
                                    </div>
                                @endif --}}

                                {{-- @if ($sentence->link_1 || $sentence->link_2 || $sentence->link_3)
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
                                @endif --}}

                                {{-- @if ($sentence->note)
                                    <div class="text-indented text-gray">
                                        <small>{{ $sentence->note_type }}:</small>
                                        <span>{{ $sentence->note }}</span>
                                    </div>
                                @endif --}}
                            </td>

                            <td class="cell-source">{{ $sentence->source }}</td>

                            <td class="cell-buttons">
                                <button class="button warning"
                                    @if ($isEditing) disabled @endif
                                    wire:click="editSentence({{ $sentence->id }})"
                                >Edit</button>
                            </td>
                        </tr>
                    @endforeach

                @endif

            </tbody>
        </table>

        {{ $sentences->links() }}

    @endif

</section>

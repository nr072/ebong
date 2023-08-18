<section class="sentence-index borderless">

    {{-- <h1>Sentences</h1> --}}

    <div class="search-fields-wrap flex justify-evenly mt-5">
        <div>
            <div>
                <input type="text"
                    wire:model="searchedSourceText"
                    class="w-96" 
                    placeholder="Type to filter source text"
                >
                @if ($searchedSourceText)
                    <button class="button"
                        wire:click="resetSearched('sourceText')"
                        title="Click to clear searched string"
                    >&times;</button>
                @endif
            </div>
            <div>
                <input type="text"
                    wire:model="searchedTargetText"
                    class="w-96" 
                    placeholder="Type to filter target text"
                >
                @if ($searchedTargetText)
                    <button class="button"
                        wire:click="resetSearched('targetText')"
                        title="Click to clear searched string"
                    >&times;</button>
                @endif
            </div>
            <div class="mt-1">
                <input type="text"
                    wire:model="searchedContext"
                    class="w-96" 
                    placeholder="Type to filter context"
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
                    placeholder="Type to filter associated group"
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
                    wire:model="searchedProject"
                    placeholder="Type to filter project"
                >
                @if ($searchedProject)
                    <button class="button"
                        wire:click="resetSearched('project')"
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
                    <th class="cell-project">Project</th>
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
                                    {{-- <span class="flag warning" title="Needs revision"></span>
                                    <span class="flag error" title="Needs revision"></span>
                                    <span class="flag info" title="Needs revision"></span>
                                    <span class="flag error" title="Needs revision"></span>
                                    <span class="flag error" title="Needs revision"></span> --}}
                                @endif
                            </td>

                            <td class="cell-group">
                                @foreach ($sentence->groups as $group)
                                    <div>{{ $group->title }}</div>
                                @endforeach
                            </td>

                            <td>

                                <div>{{ $sentence->text }}</div>

                                @foreach ($sentence->translations as $senTrans)
                                    <div>{{ $senTrans->text }}</div>
                                @endforeach

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

                            <td class="cell-project">{{ $sentence->project }}</td>

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

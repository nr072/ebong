<section class="half-width-section">

    <h1>Clusters</h1>

    <label class="input-label-set">
        <span class="input-label">Title</span>
        <input type="text" wire:model="searched" placeholder="Type to search clusters">
        @if ($searched)
            <button class="button" wire:click="resetSearched">&times;</button>
        @endif
    </label>

    @if ($clusters->count() > 0)

        <ul class="mt-4">
            @foreach ($clusters as $cluster)

                <li class="mt-2">

                    <span>{{ $cluster->title }}</span>

                    <button class="button warning mb-0 ml-1"
                        wire:click="editCluster({{ $cluster->id }})"
                        @if ($isEditing) disabled @endif
                    >Edit</button>

                    @if ($cluster->words()->count() > 0)
                        <ul>

                            @foreach ($cluster->words as $word)
                                <li>
                                    {{ $word->en }}
                                    @if ($word->pos)
                                        <small><i>{{ $word->pos->short }}</i></small>
                                    @endif
                                </li>
                            @endforeach

                        </ul>
                    @endif

                </li>

            @endforeach
        </ul>

        {{ $clusters->links() }}

    @else

        <p>Status: No clusters found</p>

    @endif
    
</section>

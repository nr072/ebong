<section class="half-width-section">

    <h1>Groups</h1>

    <p>
        <input type="text" wire:model="searched" placeholder="Type to search groups">
        @if ($searched)
            <button class="button" wire:click="resetSearched">&times;</button>
        @endif
    </p>

    @if ($groups->count() > 0)

        <ul>
            @foreach ($groups as $group)

                <li class="mt-2">

                    <span>{{ $group->title }}</span>

                    <button class="button warning mb-0 ml-1"
                        wire:click="editGroup({{ $group->id }})"
                        @if ($isEditing) disabled @endif
                    >Edit</button>

                    @if ($group->words()->count() > 0)
                        <ul>

                            @foreach ($group->words as $word)
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

        {{ $groups->links() }}

    @else

        <p>Status: No groups found</p>

    @endif
    
</section>

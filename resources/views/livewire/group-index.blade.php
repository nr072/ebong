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

                <li>{{ $group->title }}</li>

                @if ($group->words()->count() > 0)

                    <ul>
                        @foreach ($group->words as $word)
                            <li>
                                {{ $word->en }}
                                @if ($word->pos) ({{ $word->pos->en }}) @endif
                            </li>
                        @endforeach
                    </ul>

                @endif

            @endforeach
        </ul>

        {{ $groups->links() }}

    @else

        <p>Status: No groups found</p>

    @endif
    
</section>

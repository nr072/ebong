<section>

    <h1>Words</h1>

    <div>
        <input type="text" wire:model="searchedEn" placeholder="Type to search en words">
        @if ($searchedEn)
            <button wire:click="resetSearchedEn">&times;</button>
        @endif
    </div>

    @if ($words->count() < 1)

        <p>Status: No words found</p>

    @else

        <ul>

            @foreach ($words as $word)
                <li class="single-word">
                    <span>{{ $word->en }}</span>
                </li>
            @endforeach

        </ul>

        {{ $words->links() }}

    @endif

</section>

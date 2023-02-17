<section>

    <h1>Terms</h1>

    <div>
        <input type="text" wire:model="searchedEn" placeholder="Type to search en terms">
        @if ($searchedEn)
            <button wire:click="resetSearchedEn">&times;</button>
        @endif
    </div>

    @if ($terms->count() < 1)

        <p>Status: No terms found</p>

    @else

        <ul>

            @foreach ($terms as $term)
                <li class="single-term">
                    <span>{{ $term->en }}</span>
                </li>
            @endforeach

        </ul>

        {{ $terms->links() }}

    @endif

</section>

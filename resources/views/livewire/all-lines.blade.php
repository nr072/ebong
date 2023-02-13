<section class="all-lines-page" style="border: 1px solid #ccc; padding: 1rem; margin: 1rem;">

    <h1>all</h1>

    <div>
        <input type="text" wire:model="searchedEn" placeholder="Type to search en lines">
        <button wire:click="resetSearchedEn">&times;</button>
    </div>

    <ol>

        @foreach ($lines as $line)
            <li class="single-line">

                <span>{{ $line->en }} -- {{ $line->bn }}</span>

                <input type="checkbox" id="edit-line-{{ $line->id }}">

                <input type="text" name="" wire:model="newEn">
                <button class="save-btn" wire:click="save">Save</button>

                <button id="line-{{ $line->id }}-edit-btn" class="edit-btn">
                    <label for="edit-line-{{ $line->id }}">Edit</label>
                </button>

            </li>
        @endforeach

    </ol>

    {{ $lines->links() }}

</section>

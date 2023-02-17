<section>

    <h1>Examples</h1>

    <div>
        <input type="text" wire:model="searchedEn" placeholder="Type to search en examples">
        @if ($searchedEn)
            <button wire:click="resetSearchedEn">&times;</button>
        @endif
    </div>

    @if ($examples->count() < 1)

        <p>Status: No examples found</p>

    @else

        <ul>

            @foreach ($examples as $example)
                <li class="single-example">

                    <p><span class="label">en:</span> {{ $example->en }}</p>
                    @if ($example->bn)
                        <p><span class="label">bn:</span> {{ $example->bn }}</p>
                    @endif

                    @if ($example->source)
                        <p><span class="label">Source:</span> {{ $example->source }}</p>
                    @endif

                    {{-- If no links exist, no need to show the line for links. --}}
                    @if ( $example->link_1 || $example->link_2 || $example->link_3 )
                        <p><span class="label">Links:</span>
                            @if ($example->link_1)
                                <a href="{{ $example->link_1 }}">#1</a>&nbsp;
                            @endif
                            @if ($example->link_2)
                                <a href="{{ $example->link_2 }}">#2</a>&nbsp;
                            @endif
                            @if ($example->link_3)
                                <a href="{{ $example->link_3 }}">#3</a>
                            @endif
                        </p>
                    @endif

                </li>
            @endforeach

        </ul>

        {{ $examples->links() }}

    @endif

</section>

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


        <table class="alt-rows">
            <thead>
                <tr>
                    <th class="cell-term">Term</th>

                    <th class="cell-en">en</th>
                    <th class="cell-bn">bn</th>

                    <th class="cell-context">Context</th>

                    <th class="cell-source">Source</th>

                    <th class="cell-links">Links</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($examples as $example)
                    <tr>
                        <td class="cell-term">{{ $example->term->en }}</td>

                        <td class="cell-en">{{ $example->en }}</td>
                        <td class="cell-bn">{{ $example->bn }}</td>

                        <td class="cell-context">
                            @if ($example->context)
                                <div>{{ $example->context }}</div>
                            @endif
                            @if ($example->subcontext)
                                <div>{{ $example->subcontext }}</div>
                            @endif
                        </td>

                        <td class="cell-source">{{ $example->source }}</td>

                        <td class="cell-links">
                            <span>
                                @if ($example->link_1)
                                    <a href="{{ $example->link_1 }}">#1</a>
                                @endif
                            </span>
                            <span>
                                @if ($example->link_2)
                                    <a href="{{ $example->link_2 }}">#2</a>
                                @endif
                            </span>
                            <span>
                                @if ($example->link_3)
                                    <a href="{{ $example->link_3 }}">#3</a>
                                @endif
                            </span>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        {{ $examples->links() }}

    @endif

</section>

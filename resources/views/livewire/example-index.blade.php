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
                    <th>en</th>
                    <th>bn</th>

                    <th>Context</th>

                    <th>Source</th>

                    <th>Links</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($examples as $example)
                    <tr>
                        <td>{{ $example->en }}</td>
                        <td>{{ $example->bn }}</td>

                        <td>
                            @if ($example->context)
                                <div>{{ $example->context }}</div>
                            @endif
                            @if ($example->subcontext)
                                <div>{{ $example->subcontext }}</div>
                            @endif
                        </td>

                        <td class="text-center">{{ $example->source }}</td>

                        <td class="text-center">
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

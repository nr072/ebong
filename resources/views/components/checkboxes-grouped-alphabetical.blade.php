<div class="assoc-term-sets-wrap">

    @php

        // 'a'
        $asciiCode = 97;

        // Every set of inputs is wrapped in its own <div>. The first
        // one is started here.
        echo '<div class="assoc-term-set-wrap">';

    @endphp

    @foreach ($options as $option)
        @php

            $id = $option[$optIdKey];
            $value = $option[$optValueKey];

            // If the ASCII code and the first letter of the current option
            // don't match, the ASCII code's value is incremented.
            while ( chr($asciiCode) !== strtolower(substr($value, 0, 1)) && $asciiCode <= 122 ) {
                ++$asciiCode;

                // Increment happened. Now if the values match, the previous
                // <div> is ended and a new one for the next set of inputs is
                // started.
                // If the ASCII code has reached the maximum value though, the
                // previous <div> is simply ended (without starting a new one).
                if ( chr($asciiCode) === strtolower(substr($value, 0, 1)) ) {
                    if ($asciiCode === 122) {
                        echo '</div>';
                    } else {
                        echo '</div><div class="assoc-term-set-wrap">';
                    }
                }

            }

        @endphp

        {{-- Whenever the ASCII code and the first letter of the current
            option match, the <input> element is printed. --}}
        <label>
            <input wire:model="{{ $livewireModel }}"
                type="checkbox"
                name="{{ $ckbxNameValue }}"
                value="{{ $id }}"
            >
            {{ $value }}
        </label>

    @endforeach

</div>
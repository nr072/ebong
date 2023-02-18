<select wire:model={{ $livewireModel }}>

    <option value="0">{{ $defaultOptValue }}</option>

    @php
        // 'a'
        $asciiCode = 97;
    @endphp

    <optgroup label="{{ chr($asciiCode - 32) }}"></optgroup>

    @foreach ($options as $option)
        @php

            $id = $option[$optIdKey];
            $value = $option[$optValueKey];

            // Until the first letter of a value (option) matches the next
            // letter in the alphabet for the group title, the check continues.
            // When it does match, the capital letter is printed and the loop
            // is broken in order to print it options.
            while ( chr($asciiCode) !== strtolower(substr($value, 0, 1)) && $asciiCode <= 122 ) {
                ++$asciiCode;

                if ( chr($asciiCode) === strtolower(substr($value, 0, 1)) ) {
                    echo '<optgroup label="' . chr($asciiCode - 32) . '"></optgroup>';
                }

            }

        @endphp

        <option value="{{ $id }}">{{ $value }}</option>

    @endforeach

</select>

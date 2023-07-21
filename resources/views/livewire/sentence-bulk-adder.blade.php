<section class="sentence-bulk-adder">

    <h1>Add sentences in bulk</h1>

    <p class="mb-2">Enter JSON below:</p>

    <div>
        <textarea wire:model.lazy="input"
            style="width: 100%; max-width: 100%; min-height: 40vh;"
            placeholder="{{ $taPlaceholder }}"
        ></textarea>
        @error ('input')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    @if ($json)
        <div class="mt-2">
            <details>
                <summary class="cursor-p">Validated JSON preview of input</summary>
                <pre class="json-preview mt-2">{{ $json }}</pre>
            </details>
        </div>
    @endif

    <p class="mb-2">
        <button class="button"
            wire:click="sendOneSentenceDataToAdder()"
            title="Click to fill the (regular) adder with one sentence's data" 
        >Fill a sentence</button>
    </p>

    <p class="mt-2">Data remaining for {{ sizeof($sentences) }} sentence{{ sizeof($sentences) > 1 ? 's' : '' }}</p>

</section>

<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Illuminate\Support\Facades\Log;

class SentenceBulkAdder extends Component
{

    // The JSON input, directly bound to HTML.
    public $input;

    // The validated version of the JSON input.
    public $json;

    // The PHP counterpart of the JSON will be saved here to be used..
    public $sentences = [];

    // A sample JSON to be shown inside the <textarea> element.
    private $taPlaceholder = '[' . "\n" .
        "\t" . '{' . "\n" .
            "\t\t" . '"sourceText": "...",' . "\n" .
            "\t\t" . '"sourceLang": "...",' . "\n" .
            "\t\t" . '"targetText": "...",' . "\n" .
            "\t\t" . '"targetLang": "...",' . "\n" .
            "\t\t" . '"context": "...",' . "\n" .
            "\t\t" . '"subcontext": "...",' . "\n" .
            "\t\t" . '"project": "...",' . "\n" .
            "\t\t" . '"link1": "...",' . "\n" .
            "\t\t" . '"link2": "...",' . "\n" .
            "\t\t" . '"link3": "..."' . "\n" .
        "\t" . '},' . "\n" .
    ']';



    protected $rules = [
        'input' => 'required|json',
    ];



    // If sentence data exists, one sentence's data is sent to the (regular)
    // sentence adder for (manual) sentence creation.
    public function sendOneSentenceDataToAdder()
    {

        // This helps prevent sending old data to the (regular) adder if
        // the input field is ever cleared and then this button is clicked.
        $validatedData = $this->validate();

        if (sizeof($this->sentences) > 0) {
            $sentence = array_pop($this->sentences);
            $this->emitTo('sentence-adder', 'bAdderDataSent', $sentence);
        }

    }



    // Whenever the input updates, the JSON preview automatically updates
    // and the data is converted to a PHP associative array.
    public function updatedInput()
    {
        $validatedData = $this->validate();
        $this->json = $validatedData['input'];
        $this->sentences = json_decode($this->json, true);
    }



    public function render()
    {
        return view('livewire.sentence-bulk-adder', [
            'taPlaceholder' => $this->taPlaceholder,
        ]);
    }

}

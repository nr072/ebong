<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Term;
use App\Models\Example;

use Illuminate\Support\Facades\Log;

class ExampleAdder extends Component
{

    public $newTextEn = '';
    public $newTextBn = '';
    
    public $context = '';
    public $subcontext = '';

    public $source = '';
    public $link1 = '';
    public $link2 = '';
    public $link3 = '';

    // Refers to the ID of the associated term.
    public $termId = '';

    // Visual cues to let the user know things are happening (or maybe
    // not happening).
    public $status = [
        'type' => '',
        'text' => 'Clean'
    ];

    protected $rules = [
        'newTextEn' => 'required|string',
        'newTextBn' => 'nullable|string',
        'context' => 'nullable|max:200',
        'subcontext' => 'nullable|max:200',
        'source' => 'nullable|max:100',
        'link1' => 'nullable|max:200',
        'link2' => 'nullable|max:200',
        'link3' => 'nullable|max:200',
        'termId' => 'required|numeric',
    ];



    public function addExample()
    {
        $validatedData = $this->validate();

        // Nice visual cue that things are starting.
        $this->status['type'] = 'warning';
        $this->status['text'] = 'Adding new example...';

        if (empty($this->newTextEn)) {
            $this->status['type'] = 'error';
            $this->status['text'] = 'Empty example entered!';
            return;
        }

        // Example creation.
        $newExample = Example::create([
            'en' => trim( $validatedData['newTextEn'] ),
            'bn' => trim( $validatedData['newTextBn'] ),
            'context' => trim( $validatedData['context'] ),
            'subcontext' => trim( $validatedData['subcontext'] ),
            'source' => trim( $validatedData['source'] ),
            'link_1' => trim( $validatedData['link1'] ),
            'link_2' => trim( $validatedData['link2'] ),
            'link_3' => trim( $validatedData['link3'] ),
            'term_id' => $validatedData['termId']
        ]);

        // Association to... associated term.
        $term = Term::find($this->termId);
        $term->examples()->save($newExample);

        // Fields are cleared.
        $this->reset();

        // Nice visual cue again.
        if ($newExample) {
            $this->status['type'] = 'success';
            $this->status['text'] = 'New example added';
        }
    }



    public function render()
    {
        return view('livewire.example-adder', [
            'terms' => Term::all()
        ]);
    }

}
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

    public $searchedAssocTerm;

    protected $queryString = [
        'searchedAssocTerm' => ['except' => '', 'as' => 'assoc'],
    ];

    private $filteredAssocTerms;

    // Refers to the ID of the associated term.
    public $chosenAssocTermIds = [];

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
        'chosenAssocTermIds' => 'required|array',
        'chosenAssocTermIds.*' => 'required|numeric',
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
        ]);

        // For each associated term, a relation to the example is formed.
        foreach ($validatedData['chosenAssocTermIds'] as $key => $value) {
            $term = Term::find($value);
            $term->examples()->save($newExample);
        }

        // Input fields are cleared.
        $this->reset();

        if ($newExample) {

            // Nice visual cue again.
            $this->status['type'] = 'success';
            $this->status['text'] = 'New example added';

            // An event is emitted so that other Livewire components can
            // detect this and update if needed.
            $this->emit('exampleCreated');

        }
    }



    public function associateTerm($id)
    {
        array_push($this->chosenAssocTermIds, $id);
        $this->reset('searchedAssocTerm');
    }



    public function render()
    {

        if ($this->searchedAssocTerm) {
            $this->filteredAssocTerms = Term::orderBy('en')
                                ->where('en', 'like', $this->searchedAssocTerm.'%');
        } else {
            $this->filteredAssocTerms = Term::orderBy('en');
        }

        $this->filteredAssocTerms = $this->filteredAssocTerms->pluck('en', 'id');

        // Terms are returned alphabetically sorted so that they can be
        // shown in groups using the HTML <optgroup> element.
        return view('livewire.example-adder', [
            'terms' => Term::orderBy('en')->get(),
            'filteredAssocTerms' => $this->filteredAssocTerms,
        ]);

    }

}
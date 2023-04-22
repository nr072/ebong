<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sentence;
use App\Models\Word;

use Illuminate\Support\Facades\Log;

class SentenceAdder extends Component
{

    public $inputs = [];



    public $searchedAssocWord;

    protected $queryString = [
        'searchedAssocWord' => ['except' => '', 'as' => 'assoc'],
    ];



    private $filteredAssocWords;

    // Refers to the ID of the associated word.
    public $chosenAssocWordIds = [];



    // Visual cues to let the user know things are happening (or maybe
    // not happening).
    public $status = [
        'type' => '',
        'text' => 'Clean'
    ];



    // List of sources to show in a dropdown.
    public $sources;

    public $canShowSourceDropdown = true;



    // Validation rules.
    protected $rules = [
        'inputs.*.en' => 'required|string',
        'inputs.*.bn' => 'nullable|string',
        'inputs.*.context' => 'nullable|max:200',
        'inputs.*.subcontext' => 'nullable|max:200',
        'inputs.*.source' => 'required|nullable|max:100',
        'inputs.*.link1' => 'nullable|max:200',
        'inputs.*.link2' => 'nullable|max:200',
        'inputs.*.link3' => 'nullable|max:200',
        'chosenAssocWordIds' => 'required|array',
        'chosenAssocWordIds.*' => 'required|numeric',
    ];



    public function addSentence()
    {
        $validatedData = $this->validate();

        // Nice visual cue that things are starting.
        $this->status['type'] = 'warning';
        $this->status['text'] = 'Adding new sentence...';

        if (empty($this->inputs[0]['en'])) {
            $this->status['type'] = 'error';
            $this->status['text'] = 'Empty sentence entered!';
            return;
        }

        // A sentence is created for each set of sentence-related inputs.
        foreach ($validatedData['inputs'] as $validInput) {

            $newSentence = Sentence::create([
                'en' => trim( $validInput['en'] ),
                'bn' => trim( $validInput['bn'] ),
                'context' => trim( $validInput['context'] ),
                'subcontext' => trim( $validInput['subcontext'] ),
                'source' => trim( $validInput['source'] ),
                'link_1' => trim( $validInput['link1'] ),
                'link_2' => trim( $validInput['link2'] ),
                'link_3' => trim( $validInput['link3'] ),
            ]);

            // For each associated word, a relation to the sentence is formed.
            foreach ($validatedData['chosenAssocWordIds'] as $key => $value) {
                $word = Word::find($value);
                $word->sentences()->save($newSentence);
            }

        }

        // Input fields are cleared.
        $this->reset();

        if ($newSentence) {

            // Nice visual cue again.
            $this->status['type'] = 'success';
            $this->status['text'] = 'New sentence added';

            // An event is emitted so that other Livewire components can
            // detect this and update if needed.
            $this->emit('sentenceCreated');

        }

        // The array for sentence-related inputs needs at least one item
        // for the input fields on the page to show up.
        $this->insertArrayForNewSentece();
    }



    public function associateWord($id)
    {
        array_push($this->chosenAssocWordIds, $id);
        $this->reset('searchedAssocWord');

        // Used for focusing the assoc word input field.
        $this->emit('word-associated');
    }



    public function dissociateWord($id)
    {
        if (in_array($id, $this->chosenAssocWordIds)) {
            unset($this->chosenAssocWordIds[ array_search($id, $this->chosenAssocWordIds) ]);
        }

        // Used for focusing the assoc word input field.
        $this->emit('word-dissociated');
    }



    public function addAnotherSentence()
    {
        $this->insertArrayForNewSentece();
        $this->toggleSourceDropdown(1);
    }



    // An array full of inputs for a sentence is inserted into the main
    // array (for all the inputs) so that a/another set of (empty) input
    // fields can show up on the page.
    private function insertArrayForNewSentece()
    {
        array_push(
            $this->inputs, 
            [
                'en' => '',
                'bn' => '',
                'context' => '',
                'subcontext' => '',
                'source' => '',
                'link1' => '',
                'link2' => '',
                'link3' => '',
            ]
        );
    }



    // An existing source is selected from a dropdown.
    public function selectSource($index, $source)
    {
        $this->inputs[$index]['source'] = $source;
        $this->toggleSourceDropdown(0);
    }



    public function toggleSourceDropdown($canShow)
    {
        $this->canShowSourceDropdown = $canShow === 1 ? true : false;
    }



    public function mount()
    {
        // The array for sentence-related inputs needs at least one item
        // for the input fields on the page to show up.
        $this->insertArrayForNewSentece();
    }



    public function render()
    {

        $this->sources = Sentence::groupBy('source')->pluck('source');

        if ($this->searchedAssocWord) {
            $this->filteredAssocWords = Word::orderBy('en')
                                ->where('en', 'like', $this->searchedAssocWord.'%');
        } else {
            $this->filteredAssocWords = Word::where('id', 0);
        }

        $this->filteredAssocWords = $this->filteredAssocWords->pluck('en', 'id');

        // Words are returned alphabetically sorted so that they can
        // preferrably be shown in groups.
        return view('livewire.sentence-adder', [
            'words' => Word::orderBy('en')->get(),
            'filteredAssocWords' => $this->filteredAssocWords,
            'sources' => $this->sources,
        ]);

    }

}
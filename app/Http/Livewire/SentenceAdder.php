<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sentence;
use App\Models\Word;

use Illuminate\Support\Facades\Log;

class SentenceAdder extends Component
{

    public $newTextEn = '';
    public $newTextBn = '';
    
    public $context = '';
    public $subcontext = '';

    public $source = '';
    public $link1 = '';
    public $link2 = '';
    public $link3 = '';

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

    protected $rules = [
        'newTextEn' => 'required|string',
        'newTextBn' => 'nullable|string',
        'context' => 'nullable|max:200',
        'subcontext' => 'nullable|max:200',
        'source' => 'nullable|max:100',
        'link1' => 'nullable|max:200',
        'link2' => 'nullable|max:200',
        'link3' => 'nullable|max:200',
        'chosenAssocWordIds' => 'required|array',
        'chosenAssocWordIds.*' => 'required|numeric',
    ];



    public function addSentence()
    {
        $validatedData = $this->validate();

        // Nice visual cue that things are starting.
        $this->status['type'] = 'warning';
        $this->status['text'] = 'Adding new sentence...';

        if (empty($this->newTextEn)) {
            $this->status['type'] = 'error';
            $this->status['text'] = 'Empty sentence entered!';
            return;
        }

        // Sentence creation.
        $newSentence = Sentence::create([
            'en' => trim( $validatedData['newTextEn'] ),
            'bn' => trim( $validatedData['newTextBn'] ),
            'context' => trim( $validatedData['context'] ),
            'subcontext' => trim( $validatedData['subcontext'] ),
            'source' => trim( $validatedData['source'] ),
            'link_1' => trim( $validatedData['link1'] ),
            'link_2' => trim( $validatedData['link2'] ),
            'link_3' => trim( $validatedData['link3'] ),
        ]);

        // For each associated word, a relation to the sentence is formed.
        foreach ($validatedData['chosenAssocWordIds'] as $key => $value) {
            $word = Word::find($value);
            $word->sentences()->save($newSentence);
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



    public function render()
    {

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
        ]);

    }

}
<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sentence;
use App\Models\Word;

use Illuminate\Support\Facades\Log;

class SentenceEditor extends Component
{

    public $canShowEditor = false;

    // The sentence that's being edited.
    public $sentence = [];



    public $searchedAssocWord;

    protected $queryString = [
        'searchedAssocWord' => ['except' => '', 'as' => 'assoc'],
    ];



    public $filteredAssocWords;

    // Refers to the ID of the associated word.
    public $chosenAssocWordIds = [];



    // Validation rules.
    protected $rules = [
        'sentence.bn' => 'nullable|string',
        'sentence.context' => 'nullable|max:2',
        'sentence.subcontext' => 'nullable|max:200',
        'sentence.source' => 'required|nullable|max:100',
        'sentence.link_1' => 'nullable|max:200',
        'sentence.link_2' => 'nullable|max:200',
        'sentence.link_3' => 'nullable|max:200',
        'chosenAssocWordIds' => 'required|array',
        'chosenAssocWordIds.*' => 'required|numeric',
    ];



    // Certain functions are executed when certian events have been emitted.
    protected $listeners = [
        'editButtonClicked' => 'editSentence',
    ];



    private function toggleEditor($canShow = 0)
    {
        $this->canShowEditor = $canShow === 1 ? true : false;
    }

    public function showEditor()
    {
        $this->toggleEditor(1);
    }

    public function closeEditor()
    {
        $this->reset();
        $this->toggleEditor(0);
        $this->emitTo('sentence-index', 'editorClosed');
    }



    // The editor is displayed with the selected sentence's data in its
    // input fields.
    public function editSentence(Sentence $sentenceToEdit)
    {
        $this->sentence = $sentenceToEdit;
        $this->showEditor();
    }



    // Updates the sentence and closes the editor.
    public function saveUpdates()
    {
        $validatedData = $this->validate();
        $this->sentence->update( $validatedData['sentence'] );

        // For each associated word, a relation to the sentence is formed.
        foreach ($validatedData['chosenAssocWordIds'] as $key => $value) {
            $word = Word::find($value);
            $word->sentences()->save($this->sentence);
        }

        $this->reset();

        $this->emitTo('sentence-index', 'sentenceUpdated');
        $this->emitTo('sentence-index', 'editorClosed');
    }



    public function associateWord($id)
    {
        array_push($this->chosenAssocWordIds, $id);
        $this->reset('searchedAssocWord');

        // Used for focusing the assoc word input field.
        $this->emit('sentence-editor-word-associated');
    }

    public function dissociateWord($id)
    {
        if (in_array($id, $this->chosenAssocWordIds)) {
            unset($this->chosenAssocWordIds[ array_search($id, $this->chosenAssocWordIds) ]);
        }

        // Used for focusing the assoc word input field.
        $this->emit('sentence-editor-word-dissociated');
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

        return view('livewire.sentence-editor', [
            'words' => Word::orderBy('en')->get(),
        ]);

    }

}

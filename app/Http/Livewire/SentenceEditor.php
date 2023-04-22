<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sentence;

use Illuminate\Support\Facades\Log;

class SentenceEditor extends Component
{

    public $canShowEditor = false;

    // The sentence that's being edited.
    public $sentence = [];



    // Validation rules.
    protected $rules = [
        'sentence.bn' => 'nullable|string',
        'sentence.context' => 'nullable|max:2',
        'sentence.subcontext' => 'nullable|max:200',
        'sentence.source' => 'required|nullable|max:100',
        'sentence.link_1' => 'nullable|max:200',
        'sentence.link_2' => 'nullable|max:200',
        'sentence.link_3' => 'nullable|max:200',
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

        $this->reset();

        $this->emitTo('sentence-index', 'sentenceUpdated');
        $this->emitTo('sentence-index', 'editorClosed');
    }



    public function render()
    {
        return view('livewire.sentence-editor');
    }

}

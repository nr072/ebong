<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sentence;

use Illuminate\Support\Facades\Log;

class SentenceIndex extends Component
{

    private $sentences;

    // The number of paginated entries to be returned.
    private $paginCount = 20;

    // A reset function depends on these properties.
    public $searchedEn = '';
    public $searchedBn = '';
    public $searchedContext = '';
    public $searchedWord = '';
    public $searchedSource = '';

    protected $queryString = [
        'searchedEn' => ['except' => '', 'as' => 'en'],
        'searchedBn' => ['except' => '', 'as' => 'bn'],
        'searchedContext' => ['except' => '', 'as' => 'context'],
        'searchedWord' => ['except' => '', 'as' => 'word'],
        'searchedSource' => ['except' => '', 'as' => 'source'],
    ];



    // When a sentence is being edited, all the edi buttons are hidden.
    public $isEditing = false;



    // Certain functions are executed when certian events have been emitted.
    protected $listeners = [
        'sentenceCreated' => 'render',
        'editorOpened' => 'hideEditButtons',
        'editorClosed' => 'showEditButtons',
        'sentenceUpdated' => 'render',
    ];



    // The searched string is cleared.
    // These values depend on some properties of this class.
    public function resetSearched($column)
    {
        if ($column === 'en') {
            $this->reset('searchedEn');
        } else if ($column === 'bn') {
            $this->reset('searchedBn');
        } else if ($column === 'context') {
            $this->reset('searchedContext');
        } else if ($column === 'word') {
            $this->reset('searchedWord');
        } else if ($column === 'source') {
            $this->reset('searchedSource');
        }
    }



    // When a sentence is being edited, all the edit buttons are hidden.
    // When the editor is closed, they are shown again.
    private function toggleEditButtons($canShow = 0)
    {
        $this->isEditing = $canShow === 1 ? false : true;
    }

    public function showEditButtons()
    {
        $this->toggleEditButtons(1);
    }

    public function hideEditButtons()
    {
        $this->toggleEditButtons(0);
    }



    // Clicking on an edit button hides all edit buttons and shows the
    // sentence editor.
    public function editSentence($id)
    {
        $this->toggleEditButtons(0);
        $this->emitTo('sentence-editor', 'editButtonClicked', $id);
    }



    public function render()
    {
        $this->sentences = Sentence::orderBy('en');

        /*
            If something was searched, results are filtered.
        */
        if ($this->searchedEn !== '') {
            $this->sentences = $this->sentences
                ->where('en', 'like', '%'.$this->searchedEn.'%');
        }
        if ($this->searchedBn !== '') {
            $this->sentences = $this->sentences
                ->where('bn', 'like', '%'.$this->searchedBn.'%');
        }
        // Context searching works on the subcontext column too.
        if ($this->searchedContext !== '') {
            $this->sentences = $this->sentences
                ->where(function($query) {
                    $query->where('context', 'like', '%'.$this->searchedContext.'%')
                        ->orWhere('subcontext', 'like', '%'.$this->searchedContext.'%');
                });
        }
        // The associated words exist in another table.
        if ($this->searchedWord !== '') {
            $this->sentences = $this->sentences
                ->whereHas('words', function ($query){
                    $query->where('en', 'like', '%'.$this->searchedWord.'%');
                });
        }
        if ($this->searchedSource !== '') {
            $this->sentences = $this->sentences
                ->where('source', 'like', '%'.$this->searchedSource.'%');
        }

        return view('livewire.sentence-index', [
            'sentences' => $this->sentences->paginate($this->paginCount)
        ]);
    }

}

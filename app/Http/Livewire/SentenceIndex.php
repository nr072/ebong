<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sentence;

use Illuminate\Support\Facades\Log;

class SentenceIndex extends Component
{

    private $sentences;

    // The number of paginated entries to be returned.
    private $paginCount = 50;

    // A reset function depends on these properties.
    public $searchedSourceText = '';
    public $searchedTargetText = '';
    public $searchedContext = '';
    public $searchedGroup = '';
    public $searchedProject = '';

    protected $queryString = [
        'searchedSourceText' => ['except' => '', 'as' => 'source'],
        'searchedTargetText' => ['except' => '', 'as' => 'bn'],
        'searchedContext' => ['except' => '', 'as' => 'context'],
        'searchedGroup' => ['except' => '', 'as' => 'group'],
        'searchedProject' => ['except' => '', 'as' => 'project'],
    ];



    // When a sentence is being edited, all the edit buttons are hidden.
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
        if ($column === 'sourceText') {
            $this->reset('searchedSourceText');
        } else if ($column === 'targetText') {
            $this->reset('searchedTargetText');
        } else if ($column === 'context') {
            $this->reset('searchedContext');
        } else if ($column === 'group') {
            $this->reset('searchedGroup');
        } else if ($column === 'project') {
            $this->reset('searchedProject');
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
        $this->hideEditButtons();
        $this->emitTo('sentence-editor', 'editButtonClicked', $id);
    }



    public function applySearchFilters()
    {

        $query = Sentence::orderBy('text');

        if ($this->searchedSourceText !== '') {
            $query = $query->where('text', 'like', '%'.$this->searchedSourceText.'%');
        }

        // Sentence translations exist in another table.
        if ($this->searchedTargetText !== '') {
            $query = $query->whereHas('translations', function ($query){
                        $query->where('text', 'like', '%'.$this->searchedTargetText.'%');
            });
        }

        // Context searching works on the subcontext column too.
        if ($this->searchedContext !== '') {
            $query = $query->where(function($query) {
                        $query->where('context', 'like', '%'.$this->searchedContext.'%')
                            ->orWhere('subcontext', 'like', '%'.$this->searchedContext.'%');
            });
        }

        // The associated groups exist in another table.
        if ($this->searchedGroup !== '') {
            $query = $query->whereHas('groups', function ($query){
                        $query->where('text', 'like', '%'.$this->searchedGroup.'%');
            });
        }

        if ($this->searchedProject !== '') {
            $query = $query->where('project', 'like', '%'.$this->searchedProject.'%');
        }

        $this->sentences = $query->paginate($this->paginCount);

    }



    public function render()
    {
        $this->applySearchFilters();

        return view('livewire.sentence-index', [
            'sentences' => $this->sentences,
        ]);
    }

}

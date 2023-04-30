<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Group;

use Illuminate\Support\Facades\Log;

class GroupIndex extends Component
{

    private $allGroups;

    private $paginCount = 10;



    // When a group is being edited, all the edit buttons are hidden.
    public $isEditing = false;



    // String typed in the index to see matching words.
    public $searched = '';

    protected $queryString = [
        'searched' => ['except' => '', 'as' => 'group'],
    ];



    // Certain functions are executed when certian events are emitted.
    protected $listeners = [
        'groupCreated' => 'render',
        'editorClosed' => 'showEditButtons',
        'groupUpdated' => 'render',
    ];



    // Search filters (if any) are applied.
    public function applySearchFilters()
    {
        $query = Group::orderBy('title');
        
        if ($this->searched) {
            $query = $query->where('title', 'like', '%'.$this->searched.'%');
        }

        $this->allGroups = $query->paginate($this->paginCount);

    }



    public function resetSearched()
    {
        $this->reset('searched');
    }



    public function editGroup($id)
    {
        $this->toggleEditButtons(0);
        $this->emitTo('group-editor', 'editButtonClicked', $id);
    }



    // When a group is being edited, all the edit buttons are hidden.
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



    public function render()
    {
        $this->applySearchFilters();

        return view('livewire.group-index', [
            'groups' => $this->allGroups,
        ]);
    }

}

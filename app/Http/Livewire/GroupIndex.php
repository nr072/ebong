<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Group;

use Illuminate\Support\Facades\Log;

class GroupIndex extends Component
{

    private $allGroups;

    private $paginCount = 10;



    // String typed in the index to see matching words.
    public $searched = '';

    protected $queryString = [
        'searched' => ['except' => '', 'as' => 'group'],
    ];



    // Certain functions are executed when certian events are emitted.
    protected $listeners = [
        'groupCreated' => 'render',
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



    public function render()
    {
        $this->applySearchFilters();

        return view('livewire.group-index', [
            'groups' => $this->allGroups,
        ]);
    }

}

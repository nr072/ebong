<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cluster;

use Illuminate\Support\Facades\Log;

class ClusterIndex extends Component
{

    private $allClusters;

    private $paginCount = 10;



    // When a cluster is being edited, all the edit buttons are hidden.
    public $isEditing = false;



    // String typed in the index to see matching words.
    public $searched = '';

    protected $queryString = [
        'searched' => ['except' => '', 'as' => 'cluster'],
    ];



    // Certain functions are executed when certian events are emitted.
    protected $listeners = [
        'clusterCreated' => 'render',
        'editorClosed' => 'showEditButtons',
        'clusterUpdated' => 'render',
    ];



    // Search filters (if any) are applied.
    public function applySearchFilters()
    {
        $query = Cluster::orderBy('name');
        
        if ($this->searched) {
            $query = $query->where('name', 'like', '%'.$this->searched.'%');
        }

        $this->allClusters = $query->paginate($this->paginCount);

    }



    public function resetSearched()
    {
        $this->reset('searched');
    }



    public function editCluster($id)
    {
        $this->toggleEditButtons(0);
        $this->emitTo('cluster-editor', 'editButtonClicked', $id);
    }



    // When a cluster is being edited, all the edit buttons are hidden.
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

        return view('livewire.cluster-index', [
            'clusters' => $this->allClusters,
        ]);
    }

}

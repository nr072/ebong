<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Word;
use App\Models\Cluster;

use Illuminate\Support\Facades\Log;

class ClusterAdder extends Component
{

    public $name = "";

    // Once a word has been added to a cluster, it shouldn't show up as
    // a suggestion to be added to another cluster.
    private $clusterlessWords = [];



    // String typed in the adder to see matching words.
    public $searchedWord = '';

    protected $queryString = [
        'searchedWord' => ['except' => '', 'as' => 'af-word'],
        'name' => ['except' => '', 'as' => 'af-name'],
    ];

    // Contains the results after the search filter has been applied.
    private $filteredWords = [];



    // The IDs of the selected assoc words. These are used for showing
    // which words have been selected so far.
    public $chosenWordIds = [];



    // For visual cues.
    public $status = [
        'type' => '',
        'text' => 'Clean',
    ];



    // Validation rules.
    protected $rules = [
        'name' => 'required|string|max:50',
        'chosenWordIds' => 'required|array|min:1',
        'chosenWordIds.*' => 'numeric',
    ];



    public function createCluster()
    {

        // Nice visual cue that things are starting.
        $this->status['type'] = 'warning';
        $this->status['text'] = 'Trying to add...';

        $validatedData = $this->validate();

        // Cluster creation.
        $newCluster = Cluster::create([
            'name' => $validatedData['name'],
        ]);

        // Selected words are included in the newly created cluster.
        foreach ($validatedData['chosenWordIds'] as $id) {
            $word = Word::find($id);
            $word->cluster_id = $newCluster->id;
            $word->save();
        }

        // Input fields are cleared.
        $this->reset();

        if ($newCluster) {

            // Nice visual cue again.
            $this->status['type'] = 'success';
            $this->status['text'] = 'New cluster created';

            // An event is emitted in case another component needs to update.
            $this->emitTo('cluster-index', 'clusterCreated');

        }

    }



    // Search filters (if any) are applied.
    public function applySearchFilters()
    {
        if ($this->searchedWord) {
            $query = Word::where('cluster_id', null)
                            ->where('en', 'like', $this->searchedWord.'%');
        } else {
            $query = Word::where('id', 0);
        }

        // Already chosen words are excluded.
        $query = $query->whereNotIn('id', $this->chosenWordIds);

        $this->filteredWords = $query->get();
    }



    public function addWordToCluster($id)
    {
        array_push($this->chosenWordIds, $id);
        $this->reset('searchedWord');

        // Used for focusing the assoc word input field.
        $this->emit('word-added-to-cluster');
    }

    public function removeWordFromCluster($id)
    {
        if (in_array($id, $this->chosenWordIds)) {
            unset($this->chosenWordIds[ array_search($id, $this->chosenWordIds) ]);
        }

        // Used for focusing the assoc word input field.
        $this->emit('word-removed-from-cluster');
    }



    // Specified input fields are cleared.
    public function resetInput($fieldName = '') {
        $this->reset($fieldName);
    }



    public function render()
    {
        $this->clusterlessWords = Word::orderBy('en')
                                    ->where('cluster_id', null)
                                    ->select('id', 'en')
                                    ->get();

        $this->applySearchFilters();

        return view('livewire.cluster-adder', [
            'clusterlessWords' => $this->clusterlessWords,
            'filteredWords' => $this->filteredWords,
        ]);
    }

}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Line;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AllLines extends Component
{

    private $lines;

    protected $queryString = [
        'searchedEn' => ['except' => '', 'as' => 'en'],
    ];



    public $searchedEn = '';

    public $newEn = '';



    // The searched en string is cleared.
    public function resetSearchedEn()
    {
        $this->searchedEn = '';
    }



    public function save()
    {
        // code...
    }



    public function render()
    {
        $this->lines = DB::table('lines');

        if ($this->searchedEn != '') {
            $this->lines = $this->lines->where('en', 'like', '%'.$this->searchedEn.'%');
        }

// Log::debug( $this->lines->paginate() );
        return view('livewire.all-lines', [
            'lines' => $this->lines->paginate(10)
        ]);
    }


}

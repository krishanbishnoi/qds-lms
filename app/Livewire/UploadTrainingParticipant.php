<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UploadTrainingParticipant extends Component
{
    public $project;
    public $auction_id;
    public $method;
    public $userSelects;
    public $methods = ['fromExcel' => 'From Excel',  'fromUser' => 'From Users'];
    public $selected_users = [];
    public $projectName;
    public $methodName;
    public $projects = QDS_PROJECT_LIST;
    public $users = []; // You can load real users from DB

    public function mount()
    {
        // Example dummy users; ideally fetch from DB
        $this->users = User::whereNotIn('user_role_id', [1, 2, 4])->select('id', 'fullname')->get()->toArray();
    }
    public function projectChange()
    {
        $this->projectName = $this->project;
    }
    public function methodChange()
    {
        $this->methodName = $this->method;
    }
    public function render()
    {
        return view('livewire.upload-training-participant');
    }
}

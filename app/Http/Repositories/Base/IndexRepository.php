<?php


namespace App\Http\Repositories\Base;

use App\Http\Repositories\BaseRepository;

class IndexRepository extends BaseRepository
{

    public function index($data)
    {
        return [
            'id' => $data['id']
        ];
    }
}

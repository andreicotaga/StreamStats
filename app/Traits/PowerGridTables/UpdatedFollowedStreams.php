<?php

namespace App\Traits\PowerGridTables;

trait UpdatedFollowedStreams
{
    protected function getListeners(): array
    {
        return array_merge(
            parent::getListeners(),
            [
                'updatedFollowedStreams' => 'updateData',
            ]
        );
    }

    public function updateData()
    {
        $this->user->refresh();
        $this->fillData();
    }
}

<?php

function format(int $total, array $list)
{
    return [
        'draw' => request()->get('draw'),
        'recordsTotal' => $total,
        'recordsFiltered' => $total,
        'data' => $list,
    ];
}

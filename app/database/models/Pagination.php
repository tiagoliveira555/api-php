<?php

namespace app\database\models;

class Pagination
{
    private int $currentPage = 1;
    private int $totalPages = 0;
    private int $itemsPerPage = 5;
    private int $totalItems = 0;

    public function setTotalItems(int $totalItems)
    {
        $this->totalItems = $totalItems;
    }

    public function setItemsPerPage(int $itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getPagination()
    {
        return [
            'per_page'    => $this->itemsPerPage,
            'page'        => $this->currentPage,
            'total_pages' => $this->totalPages,
            'total_items' => $this->totalItems
        ];
    }

    public function getLimit()
    {
        $this->currentPage = $_GET['page'] > 0 ? $_GET['page'] : 1;
        $this->itemsPerPage = $_GET['per_page'] ?? 5;

        $offset = ($this->currentPage - 1) * $this->itemsPerPage;
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);

        return ['limit' => $this->itemsPerPage, 'offset' => $offset];
    }
}

<?php

namespace app\database\models;

class Pagination
{
    private int $currentPage = 1;
    private int $totalPages;
    private int $itemsPerPage = 10;
    private int $totalItems;

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
        return $this->currentPage . '/' . $this->totalPages;
    }

    public function calculations()
    {
        $this->currentPage = $_GET['page'] > 0 ? $_GET['page'] : 1;
        $this->itemsPerPage = $_GET['per_page'] ?? 10;

        $offset = ($this->currentPage - 1) * $this->itemsPerPage;
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);

        return ['limit' => $this->itemsPerPage, 'offset' => $offset];
    }
}

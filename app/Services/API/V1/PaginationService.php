<?php

declare(strict_types=1);

namespace App\Services\API\V1;

use Illuminate\Support\Collection;

final class PaginationService extends \Illuminate\Pagination\LengthAwarePaginator
{
    public function toArray(): array
    {
        return [
            'results' => $this->items->toArray(),
            'pagination' => [
                'links' => $this->customLinksCollection(),
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'from' => $this->firstItem(),
                'last_page' => $this->lastPage(),
                'next_page_url' => $this->nextPageUrl(),
                'to' => $this->lastItem()
            ],
        ];
    }

    public function customLinksCollection(): Collection
    {
        $onEachSide = 3;
        $currentPage = $this->currentPage();
        $total = $this->total();
        $lastPage = $this->lastPage();

        $links = [];
        $sliceLinks = [];

        if (isset($total, $this->perPage)) {
            $sliceLinks = range(1, ceil($total / $this->perPage));

            if (isset($currentPage, $onEachSide)) {
                $sliceLinks = array_slice(
                    $sliceLinks,
                    (int) max(0, min(count($sliceLinks) - $onEachSide, $currentPage - ceil($onEachSide / 2))),
                    $onEachSide
                );
            }
        }

        if ($currentPage > 1) {
            $links[] = ['active' => false, 'label' => 'first'];
            $links[] = ['active' => false, 'label' => 'prev'];
        }

        foreach ($sliceLinks as $link) {
            $links[] = ['active' => (int) $link === $currentPage, 'label' => (int) $link];
        }

        if ($currentPage < $lastPage && $this->perPage < $total) {
            $links[] = ['active' => false, 'label' => 'next'];
            $links[] = ['active' => false, 'label' => 'last'];
        }

        return collect($links);
    }
}

<?php

namespace App\Services\Platform;

use App\Models\Country;

trait CountryService
{

    /**
     * All countries
     *
     * @param bool $paginate
     * @param bool $activated
     * @param int|null $currentPage
     * @param int|null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function allCountries(bool $paginate = false, bool $activated = true, int $currentPage = null, int $perPage = null)
    {
        $queryBuilder = Country::query();
        if ($activated) {
            $queryBuilder->whereNotNull('activated_at');
        }

        if ($paginate) {
            return $queryBuilder->orderBy('designation')
                ->paginate(
                    empty($perPage) ? config('osm.constants.paginator_per_page') : $perPage,
                    ['*'],
                    'page',
                    $currentPage
                );
        }

        return $queryBuilder->orderBy('designation')->get();
    }

}

@if ($paginator->hasPages())
@php
    if(isset(Request::query()['q'])){
        $query = '&q='.Request::query()['q'];
    } else{
        $query = null;
    }
@endphp
    <nav>
        <ul class="pagination d-flex justify-content-between">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled m-0" aria-disabled="true">
                    <span class="page-link">@lang('pagination.previous')</span>
                </li>
            @else
                <li class="page-item m-0">
                    <a class="page-link" href="{{ $paginator->previousPageUrl().$query }}" rel="prev">@lang('pagination.previous')</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item m-0">
                    <a class="page-link" href="{{ $paginator->nextPageUrl().$query }}" rel="next">@lang('pagination.next')</a>
                </li>
            @else
                <li class="page-item disabled m-0" aria-disabled="true">
                    <span class="page-link">@lang('pagination.next')</span>
                </li>
            @endif
        </ul>
    </nav>
@endif

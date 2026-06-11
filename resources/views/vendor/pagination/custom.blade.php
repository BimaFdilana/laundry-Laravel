@if ($paginator->hasPages())
    <nav aria-label="Pagination">
        <ul class="pagination pagination-custom">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&lsaquo; Previous</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        aria-label="@lang('pagination.previous')">&lsaquo; Previous</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                        aria-label="@lang('pagination.next')">Next &rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">Next &rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>

    <style>
        .pagination-custom {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .pagination-custom .page-item .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 14px;
            border: 1px solid var(--pagination-border, #e2e8f0);
            border-radius: 8px;
            background: var(--pagination-bg, #ffffff);
            color: var(--pagination-color, #4a5568);
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pagination-custom .page-item .page-link:hover {
            background: var(--pagination-hover-bg, #0fb9b1);
            border-color: var(--pagination-hover-bg, #0fb9b1);
            color: #fff;
            transform: translateY(-1px);
        }

        .pagination-custom .page-item.active .page-link {
            background: linear-gradient(135deg, #0fb9b1 0%, #0a8f89 100%);
            border-color: #0fb9b1;
            color: #fff;
            box-shadow: 0 4px 10px rgba(15, 185, 177, 0.35);
        }

        .pagination-custom .page-item.disabled .page-link {
            background: var(--pagination-disabled-bg, #f7fafc);
            color: var(--pagination-disabled-color, #a0aec0);
            cursor: not-allowed;
            border-color: var(--pagination-border, #e2e8f0);
        }

        /* Dark mode support */
        body.dark-layout .pagination-custom .page-item .page-link,
        .dark-layout .pagination-custom .page-item .page-link {
            background: #283046;
            border-color: #3b4253;
            color: #d0d2d6;
        }

        body.dark-layout .pagination-custom .page-item.disabled .page-link,
        .dark-layout .pagination-custom .page-item.disabled .page-link {
            background: #1e2541;
            color: #676d7d;
            border-color: #3b4253;
        }

        body.dark-layout .pagination-custom .page-item .page-link:hover,
        .dark-layout .pagination-custom .page-item .page-link:hover {
            background: #0fb9b1;
            border-color: #0fb9b1;
            color: #fff;
        }
    </style>
@endif

@if ($paginator->hasPages())
<nav class="d-flex justify-content-between mt-3">
    {{-- Mobile view --}}
    <div class="d-flex justify-content-between flex-fill d-sm-none">
        <ul class="pagination mb-0">
            {{-- First --}}
            <!-- @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link text-muted">&laquo;&laquo;</span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link text-dark" href="{{ $paginator->url(1) }}" rel="first">&laquo;&laquo;</a>
            </li>
            @endif -->

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link text-muted">&lsaquo;</span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link text-dark" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a>
            </li>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link text-dark" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a>
            </li>
            @else
            <li class="page-item disabled">
                <span class="page-link text-muted">&rsaquo;</span>
            </li>
            @endif

            {{-- Last --}}
            <!-- @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link text-dark" href="{{ $paginator->url($paginator->lastPage()) }}"
                    rel="last">&raquo;&raquo;</a>
            </li>
            @else
            <li class="page-item disabled">
                <span class="page-link text-muted">&raquo;&raquo;</span>
            </li>
            @endif -->
        </ul>
    </div>

    {{-- Desktop view --}}
    <div class="d-none d-sm-flex align-items-center justify-content-between w-100">
        <div class="me-3">
            <p class="small text-muted">

                <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                -
                <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                trên
                <span class="fw-semibold">{{ $paginator->total() }}</span>

            </p>
        </div>

        <div>
            <ul class="pagination">
                {{-- First --}}
                <!-- @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link text-muted">&laquo;&laquo;</span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link text-dark" href="{{ $paginator->url(1) }}" rel="first">&laquo;&laquo;</a>
                </li>
                @endif -->

                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link text-muted">&lsaquo;</span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link text-dark" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a>
                </li>
                @endif

                {{-- Page Numbers --}}
                @foreach ($elements as $element)
                @if (is_string($element))
                <li class="page-item disabled"><span class="page-link text-muted">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                <li class="page-item active" aria-current="page">
                    <span class="page-link text-white bg-dark">{{ $page }}</span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link text-dark" href="{{ $url }}">{{ $page }}</a>
                </li>
                @endif
                @endforeach
                @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link text-dark" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link text-muted">&rsaquo;</span>
                </li>
                @endif

                {{-- Last --}}
                <!-- @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link text-dark" href="{{ $paginator->url($paginator->lastPage()) }}"
                        rel="last">&raquo;&raquo;</a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link text-muted">&raquo;&raquo;</span>
                </li>
                @endif -->
            </ul>
        </div>
    </div>
</nav>
@endif
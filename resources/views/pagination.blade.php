@if (isset($paginator) && $paginator->lastPage() > 1)
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group me-2" role="group" aria-label="First group">
            {{-- <a href="{{ $paginator->url($paginator->currentPage() - 1) }}{{ (isset($_GET['per_page']) ? '&per_page='.$_GET['per_page'] : '') }}" class="btn btn-primary">
                <span aria-hidden="true">&lsaquo;</span>
            </a> --}}
            @php
                $interval = isset($interval) ? abs(intval($interval)) : 3 ;
                $from = $paginator->currentPage() - $interval;
                if($from < 1){
                    $from = 1;
                }

                $to = $paginator->currentPage() + $interval;
                if($to > $paginator->lastPage()){
                    $to = $paginator->lastPage();
                }
            @endphp
            <!-- first/previous -->
            @if($paginator->currentPage() > 1)
                <a href="{{ $paginator->url(1) }}{{ (isset($_GET['per_page']) ? '&per_page='.$_GET['per_page'] : '') }}" class="btn btn-primary">
                    <span aria-hidden="true">&laquo;</span>
                </a>
                <a href="{{ $paginator->url($paginator->currentPage() - 1) }}{{ (isset($_GET['per_page']) ? '&per_page='.$_GET['per_page'] : '') }}" class="btn btn-primary">
                    <span aria-hidden="true">&lsaquo;</span>
                </a>
            @endif
            <!-- links -->
            @for($i = $from; $i <= $to; $i++)
                @php
                    $isCurrentPage = $paginator->currentPage() == $i;
                @endphp
                <a
                    href="{{ !$isCurrentPage ? $paginator->url($i) : '#' }}{{ (isset($_GET['per_page']) ? '&per_page='.$_GET['per_page'] : '') }}"
                    class="btn btn-primary {{ $isCurrentPage ? 'disabled' : '' }}"
                >
                    <span aria-hidden="true">{{ $i }}</span>
                </a>
            @endfor
            <!-- next/last -->
            @if($paginator->currentPage() < $paginator->lastPage())
                <a
                    href="{{ $paginator->url($paginator->currentPage() + 1) }}{{ (isset($_GET['per_page']) ? '&per_page='.$_GET['per_page'] : '') }}"
                    class="btn btn-primary"
                >
                    <span aria-hidden="true">&rsaquo;</span>
                </a>
                <a
                    href="{{ $paginator->url($paginator->lastpage()) }}{{ (isset($_GET['per_page']) ? '&per_page='.$_GET['per_page'] : '') }}"
                    class="btn btn-primary"
                >
                    <span aria-hidden="true">&raquo;</span>
                </a>
            @endif
            {{-- <a
                href="{{ $paginator->url($paginator->currentPage() + 1) }}{{ (isset($_GET['per_page']) ? '&per_page='.$_GET['per_page'] : '') }}"
                class="btn btn-primary"
            >
                <span aria-hidden="true">&rsaquo;</span>
            </a> --}}
        </div>
    </div>
@endif

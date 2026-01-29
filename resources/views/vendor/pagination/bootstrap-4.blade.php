@if ($paginator->hasPages())
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <div class="text-muted small mb-2 mb-md-0">
            @if ($paginator->firstItem())
                Affichage de
                <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                à
                <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                sur
                <span class="fw-semibold">{{ $paginator->total() }}</span>
                résultats
            @else
                Affichage de
                <span class="fw-semibold">{{ $paginator->count() }}</span>
                résultat(s)
            @endif
        </div>

        <nav>
            <ul class="pagination mb-0">
                {{-- Lien Précédent --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true" aria-label="Page précédente">
                        <span class="page-link" aria-hidden="true">&laquo; Précédent</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Page précédente">&laquo; Précédent</a>
                    </li>
                @endif

                {{-- Liens de pages --}}
                @foreach ($elements as $element)
                    {{-- Points de suspension --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Tableaux de liens de pages --}}
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

                {{-- Lien Suivant --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Page suivante">Suivant &raquo;</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="Page suivante">
                        <span class="page-link" aria-hidden="true">Suivant &raquo;</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif


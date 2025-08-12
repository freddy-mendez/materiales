@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" style="display: flex; justify-content: center;">
        <ul style="display: flex; list-style: none; padding: 0; margin-top: 1rem;">
            @if ($paginator->onFirstPage())
                <li style="margin: 0 0.25rem;">
                    <span style="display: inline-block; padding: 0.5rem 0.75rem; border: 1px solid #ccc; border-radius: 4px; text-decoration: none; color: #6c757d; font-size: 1rem; cursor: not-allowed;">
                        &laquo;
                    </span>
                </li>
            @else
                <li style="margin: 0 0.25rem;">
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display: inline-block; padding: 0.5rem 0.75rem; border: 1px solid #ccc; border-radius: 4px; text-decoration: none; color: #007bff; font-size: 1rem;">
                        &laquo;
                    </a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li style="margin: 0 0.25rem;">
                        <span style="display: inline-block; padding: 0.5rem 0.75rem; border: 1px solid #ccc; border-radius: 4px; text-decoration: none; color: #6c757d; font-size: 1rem;">
                            {{ $element }}
                        </span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li style="margin: 0 0.25rem;">
                                <span style="display: inline-block; padding: 0.5rem 0.75rem; border: 1px solid #007bff; border-radius: 4px; color: white; background-color: #007bff; font-size: 1rem;">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li style="margin: 0 0.25rem;">
                                <a href="{{ $url }}" style="display: inline-block; padding: 0.5rem 0.75rem; border: 1px solid #ccc; border-radius: 4px; text-decoration: none; color: #007bff; font-size: 1rem;">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li style="margin: 0 0.25rem;">
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display: inline-block; padding: 0.5rem 0.75rem; border: 1px solid #ccc; border-radius: 4px; text-decoration: none; color: #007bff; font-size: 1rem;">
                        &raquo;
                    </a>
                </li>
            @else
                <li style="margin: 0 0.25rem;">
                    <span style="display: inline-block; padding: 0.5rem 0.75rem; border: 1px solid #ccc; border-radius: 4px; text-decoration: none; color: #6c757d; font-size: 1rem; cursor: not-allowed;">
                        &raquo;
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
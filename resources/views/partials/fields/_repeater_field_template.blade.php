<script type="text/html" id="{{ $template ?? null }}">
    <div class="card repeater-item" data-sort="@{{it.block_key}}">
        <div class="card-header">
            <h5 class="actions">
                <span class="action card-handle icon-size-fullscreen" data-parent-container-key="@{{it.name}}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M11 17L7 21L3 17" stroke="#9098A3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 21V9" stroke="#9098A3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21 7L17 3L13 7" stroke="#9098A3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17 15V3" stroke="#9098A3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                @if($collapse ?? false)
                <span class="action collapse-switch" data-action="click->fields--repeater#collapse">
                    <x-orchid-icon path="arrow-down" class="small me-2 transition"></x-orchid-icon>
                </span>
                @endif
                <span class="action icon-plus" data-action="click->fields--repeater#addBlockAfter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#9098A3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 8V16" stroke="#9098A3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 12H16" stroke="#9098A3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span class="action" data-action="click->fields--repeater#deleteBlock">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#9098A3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 12H16" stroke="#9098A3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </h5>
        </div>
        <div class="card-body repeater-content">
            @{{it.content}}
        </div>
    </div>
</script>

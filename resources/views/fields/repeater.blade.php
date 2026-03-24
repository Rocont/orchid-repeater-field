@component($typeForm,get_defined_vars())
    <div class="repeater"
         data-controller="fields--repeater"
         data-fields--repeater-options="{{ json_encode($attributes->getAttributes()) }}"
         data-fields--repeater-template="{{ $template }}"
         data-fields--repeater-layout="{{ $layout }}"
         data-fields--repeater-value="{{ json_encode($value) }}"
         data-fields--repeater-ajax-data='{{ $ajax_data }}'
         data-fields--repeater-url="{{route('platform.systems.repeater')}}"
         data-fields--repeater-error-title="{{ __('Error') }}"
         data-fields--repeater-confirm-delete-message="{{ $confirmDeleteBlockText ?? __('Are you sure you want to delete this block?') }}"
         data-fields--repeater-min-error-message="{{ __('The minimum number of blocks was reached.') }}"
         data-fields--repeater-max-error-message="{{ __('The maximum number of blocks was reached.') }}"
    >
        <input type="hidden" name="{{ $name }}" data-target="fields--repeater.repeaterField" value=""/>
        <div class="row">
            <div class="col-md-12">
                <section class="content wrapper-xs mb-2 empty loading" data-target="fields--repeater.content">
                    <div class="no-value-message">
                        {{ __('Click the ":button_label" button below to start adding the items.', [
                            'button_label' => ($buttonLabel ?? __('Add block'))
                        ]) }}
                    </div>
                    <div class="loading-message">
                        <x-orchid-icon path="loading" class="me-2 loading-icon"></x-orchid-icon>
                    </div>
                    <section class="repeaters_container"
                             data-target="fields--repeater.blocks"
                             data-container-key="{{ $name }}"></section>
                </section>
                <button class="btn btn-link icon-link gap-2 add-button" type="button"
                        data-action="click->fields--repeater#addNewBlock"
                        data-target="fields--repeater.addBlockButton">
                            {{ $buttonLabel ?? __('Add block') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                </button>
            </div>
        </div>

        @include('platform::partials.fields._repeater_field_template')
    </div>
@endcomponent

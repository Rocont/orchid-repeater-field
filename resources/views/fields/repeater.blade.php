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
                <button class="btn btn-default pull-right" type="button"
                        data-action="click->fields--repeater#addNewBlock"
                        data-target="fields--repeater.addBlockButton">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#9098A3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 8V16" stroke="#9098A3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8 12H16" stroke="#9098A3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ $buttonLabel ?? __('Add block') }}
                </button>
            </div>
        </div>

        @include('platform::partials.fields._repeater_field_template')
    </div>
@endcomponent

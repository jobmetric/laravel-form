@php
    use JobMetric\Form\Tab\Tab;

    $method = $field->method;
    $method_theme = 'POST';
    if (in_array(strtolower($method), ['get', 'post'])) {
        $method_theme = $method;
    }
@endphp

<form method="{{ $method_theme }}" action="{{ $field->action }}" class="{{ $field->class }}" id="{{ $field->id }}" name="{{ $field->name }}" enctype="{{ $field->enctype }}" target="{{ $field->target }}" autocomplete="{{ $field->autocomplete ? 'on' : 'off' }}" @if($field->novalidate) novalidate @endif>
    @if ($field->csrf)
        @csrf
    @endif
    @if (!in_array(strtolower($method), ['get', 'post']))
        @method($method)
    @endif
    @foreach($field->hiddenCustomField as $customField)
        {!! $customField->render() !!}
    @endforeach

    @if(!empty($field->tabs))
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-10">
            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2 d-flex justify-content-between align-items-center"
                role="tablist">
                <div class="d-flex">
                    @foreach($field->tabs as $tab)
                        @php
                            /**
                             * @var Tab $tab
                             */
                        @endphp
                        @if($tab->position == 'start')
                            {!! $tab->renderLink() !!}
                        @endif
                    @endforeach
                </div>
                <div class="d-flex">
                    @foreach($field->tabs as $tab)
                        @php
                            /**
                             * @var Tab $tab
                             */
                        @endphp
                        @if($tab->position == 'end')
                            {!! $tab->renderLink() !!}
                        @endif
                    @endforeach
                </div>
            </ul>
            <div class="tab-content">
                @foreach($field->tabs as $tab)
                    @php
                        /**
                         * @var Tab $tab
                         */
                    @endphp
                    {!! $tab->renderData($values) !!}
                @endforeach
            </div>
        </div>
    @endif
</form>

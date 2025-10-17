@php
    use JobMetric\CustomField\CustomField;
@endphp
<div class="border border-1 p-5 pb-n5 mb-10">
    <span class="fs-3 fw-bold text-highlight" data-label-colored="{{ mb_substr(trans($field->label), 0, 2) }}"
          data-label-noncolored="{{ mb_substr(trans($field->label), 2) }}"></span>
    <hr class="mb-7" style="box-shadow:0 -4px 0 3px #ddd;">
    @if($field->description)
        <div class="alert alert-info fs-7 fw-bold mb-10">{{ trans($field->description) }}</div>
    @endif
    @php
        $count = count($field->customFields);
    @endphp
    @foreach($field->customFields as $index => $customField)
        @php
            /**
             * @var CustomField $customField
             */
            $classParent = 'mb-10';
            if ($index == $count - 1) {
                $classParent = '';
            }
            $value = $values[$customField->params['name']] ?? null;
        @endphp
        {!! $customField->toHtml($value, classParent: $classParent, hasErrorTagForm: true) !!}
    @endforeach
</div>

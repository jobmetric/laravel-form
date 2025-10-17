@php
    use JobMetric\CustomField\CustomField;
    use JobMetric\Form\Group\Group;
@endphp
<div class="tab-pane fade @if($field->selected) show active @endif" id="{{ $field->id }}" role="tabpanel">
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <div class="card card-flush py-4">
            @if($field->description)
                <div class="card-header">
                    <div class="card-title">
                        <span class="fs-7 fw-bold">{{ trans($field->description) }}</span>
                    </div>
                </div>
            @endif
            <div class="card-body">
                <div class="tab-content">
                    @php
                        $count = count($field->fields);
                    @endphp
                    @foreach($field->fields as $index => $fieldData)
                        @if($fieldData instanceof Group)
                            {!! $fieldData->toHtml($values) !!}
                        @else
                            @php
                                /**
                                 * @var CustomField $fieldData
                                 */
                                $classParent = 'mb-10';
                                if ($index == $count - 1) {
                                    $classParent = '';
                                }
                                $value = $values[$fieldData->params['name']] ?? null;
                            @endphp
                            {!! $fieldData->toHtml($value, classParent: $classParent, hasErrorTagForm: true) !!}
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

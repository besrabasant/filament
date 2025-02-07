@props([
    'id',
    'label' => null,
    'labelPrefix' => null,
    'labelSrOnly' => false,
    'helperText' => null,
    'hint' => null,
    'required' => false,
    'statePath',
])

<div {{ $attributes }}>
    @if ($label && $labelSrOnly)
        <label for="{{ $id }}" class="sr-only">
            {{ $label }}
        </label>
    @endif

    <div class="space-y-2">
        @if (($label && ! $labelSrOnly) || $hint)
            <div class="flex items-center justify-between space-x-2 rtl:space-x-reverse">
                @if ($label && ! $labelSrOnly)
                    <x-forms::field-wrapper.label
                        :for="$id"
                        :error="$errors->has($statePath)"
                        :prefix="$labelPrefix"
                        :required="$required"
                    >
                        {{ $label }}
                    </x-forms::field-wrapper.label>
                @endif

                @if ($hint)
                    <x-forms::field-wrapper.hint>
                        {!! Str::of($hint)->markdown() !!}
                    </x-forms::field-wrapper.hint>
                @endif
            </div>
        @endif

        {{ $slot }}

        @if ($errors->has($statePath))
            <x-forms::field-wrapper.error-message>
                {{ $errors->first($statePath) }}
            </x-forms::field-wrapper.error-message>
        @endif

        @if ($helperText)
            <x-forms::field-wrapper.helper-text>
                {!! Str::of($helperText)->markdown() !!}
            </x-forms::field-wrapper.helper-text>
        @endif
    </div>
</div>

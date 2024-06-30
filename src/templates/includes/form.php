<?php
echo $form->render(
    boilerplate: '<div class="{groupClass}">
                <label for="{id}" class="{labelClass}">{label}:</label>
                <div>
                    {field}
                    {errors}
                </div>
            </div>',
    class: [
        'groupClass' => 'my-2 border-b py-2 border-zinc-700/75 flex flex-col md:flex-row md:items-center',
        'labelClass' => 'md:min-w-40 mb-2 md:mb-0 font-semibold',
        'inputClass' => 'px-2 py-1 border border-zinc-700/75 text-sm rounded bg-zinc-800',
        'inputErrorClass' => 'border-red-200 bg-red-100',
        'checkboxClass' => 'flex items-center gap-1',
        'checkboxErrorClass' => 'text-red-600',
        'textareaClass' => 'px-2 min-w-72 md:min-w-96 py-1 border border-zinc-700/75 text-sm rounded bg-zinc-800',
        'textareaErrorClass' => 'border-red-200 bg-red-100',
        'selectClass' => 'px-2 min-w-52 md:min-w-72 py-1 border border-zinc-700/75 text-sm rounded bg-zinc-800',
        'selectErrorClass' => 'border-red-200 bg-red-100',
        'radioClass' => 'mr-2',
        'radioErrorClass' => 'text-red-600',
        'errorListClass' => 'px-2 py-1 bg-red-100 border-red-200 mt-1',
        'errorListItemClass' => 'text-sm'
    ]
);

<template>
    <div class="col-span-6 md:col-span-6 flex mb-4">
        <div class="botonera">
            <slot name="buttons" />
        </div>
        <div class="px-4 sm:px-0" style="width: 100%; text-align: right;">
            <h3 class="text-lg font-medium text-gray-900">
                <slot name="title" />
            </h3>

            <p class="mt-1 text-sm text-gray-600">
                <slot name="description" />
            </p>
        </div>

        <div class="px-4 sm:px-0">
            <slot name="aside" />
        </div>
    </div>
</template>

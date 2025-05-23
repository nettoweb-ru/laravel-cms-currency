<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain" :header="$header">
    <x-cms-form id="currency" :url="$url" :method="$method" :objectId="$object->id" :lang="true">
        <x-slot name="sheet1">
            <x-cms::form.string name="sort" width="1" maxlength="3"
                :label="__('main.attr_sort')"
                :value="old('sort', $object->getAttribute('sort'))"
                :messages="$errors->get('sort')"
            />
            <x-cms::form.string name="name" width="5" maxlength="255"
                :label="__('main.attr_name')"
                :value="old_multilingual('name', $object)"
                :messages="errors_multilingual('name', $errors)"
                :required="true"
                :autofocus="true"
                :multilang="true"
            />
            <x-cms::form.string name="slug" width="3" maxlength="3"
                :label="__('main.attr_slug')"
                :value="old('slug', $object->getAttribute('slug'))"
                :messages="$errors->get('slug')"
                :required="true"
            />
            <x-cms::form.checkbox name="is_default" width="3"
                :label="__('main.attr_is_default')"
                :value="old('is_default', $object->getAttribute('is_default'))"
                :messages="$errors->get('is_default')"
                :options="$reference['boolean']"
            />
        </x-slot>
    </x-cms-form>
</x-cms::layout.admin>

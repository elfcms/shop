<div class="item-form">
    <h2>{{ $page['title'] }}</h2>
    <form action="{{ route('admin.shop.items.pictures.store',$item) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="colored-rows-box">
            <div class="input-box colored">
                <label for="active">
                    {{ __('elfcms::default.active') }}
                </label>
                <div class="input-wrapper">
                    <x-elfcms::ui.checkbox.switch name="active" id="active" checked="{{ true }}" />
                </div>
            </div>
            <div class="input-box colored">
                <label for="image">{{ __('elfcms::default.image') }}</label>
                <div class="input-wrapper">
                    <x-elf-input-file value="" :params="['name' => 'image', 'code' => 'image']" :download="true" accept=".jpg,.jpeg,.png,.webp" />
                </div>
            </div>
            @if ($params['is_preview'])
            <div class="input-box colored">
                <label for="preview">{{ __('elfcms::default.preview') }}</label>
                <div class="input-wrapper">
                    <x-elf-input-file value="" :params="['name' => 'preview', 'code' => 'preview']" :download="true" accept=".jpg,.jpeg,.png,.webp" />
                </div>
            </div>
            @endif
            @if ($params['is_thumbnail'])
            <div class="input-box colored">
                <label for="thumbnail">{{ __('elfcms::default.thumbnail') }}</label>
                <div class="input-wrapper">
                    <x-elf-input-file value="" :params="['name' => 'thumbnail', 'code' => 'thumbnail']" :download="true" accept=".jpg,.jpeg,.png,.webp" />
                </div>
            </div>
            @endif
            <div class="input-box colored">
                <label for="position">{{ __('elfcms::default.position') }}</label>
                <div class="input-wrapper">
                    <input type="number" name="position" id="position" value="{{$position}}">
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="button color-text-button success-button">{{ __('elfcms::default.save') }}</button>
                @if (empty($isAjax))
                    <button type="submit" name="submit" value="save_and_close"
                        class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                    <a href="{{ route('admin.shop.item.edit',$item) }}"
                        class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
                @endif
            </div>
    </form>
</div>
<script>
const previewInput = document.querySelector('#preview')
if (previewInput) {
    inputFileImg(previewInput)
}
const imageInput = document.querySelector('#image')
if (imageInput) {
    inputFileImg(imageInput)
}
const thumbnailInput = document.querySelector('#thumbnail')
if (thumbnailInput) {
    inputFileImg(thumbnailInput)
}
autoSlug('.autoslug')


shopPicTagFormInit()

//add editor
runEditor('#description')
runEditor('#additional_text')
</script>

<div class="item-form">
    <h3>{{ $page['title'] }}</h3>
    <form action="{{ route('admin.shop.items.pictures.update',['item'=>$item,'picture'=>$picture]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $picture->id }}">
        <div class="colored-rows-box">
            <div class="input-box colored">
                <x-elfcms-input-checkbox code="active" label="{{ __('elfcms::default.active') }}" :checked="$picture->active" style="blue" />
            </div>
            <div class="input-box colored">
                <label for="image">{{ __('elfcms::default.image') }}</label>
                <div class="input-wrapper">
                    <x-elfcms-input-image code="image" value="{{ $picture->image }}" />
                </div>
            </div>
            @if ($params['is_preview'])
            <div class="input-box colored">
                <label for="preview">{{ __('elfcms::default.preview') }}</label>
                <div class="input-wrapper">
                    <x-elfcms-input-image code="preview" value="{{ $picture->preview }}" />
                </div>
            </div>
            @endif
            @if ($params['is_thumbnail'])
            <div class="input-box colored">
                <label for="thumbnail">{{ __('elfcms::default.thumbnail') }}</label>
                <div class="input-wrapper">
                    <x-elfcms-input-image code="thumbnail" value="{{ $picture->thumbnail }}" />
                </div>
            </div>
            @endif

            <div class="input-box colored">
                <label for="position">{{ __('elfcms::default.position') }}</label>
                <div class="input-wrapper">
                    <input type="number" name="position" id="position" value="{{ $picture->position }}">
                </div>
            </div>
        </div>
        <div class="button-box single-box">
            <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.submit') }}</button>
        </div>
    </form>
</div>
<script>
const previewInput = document.querySelector('#preview')
console.log(previewInput)
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
</script>

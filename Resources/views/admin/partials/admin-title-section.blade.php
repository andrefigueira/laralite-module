<div class="admin-title-section d-flex justify-content-between flex-wrap flex-md-nowrap">
    <h1 class="admin-title">{{ $title }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group">
            @if(!empty($buttons))
                @foreach($buttons as $button)
                    <a href="{{ $button['href'] }}" class="btn btn-success"><i class="ri-add-line align-middle mr-2"></i>{{ $button['label'] }}</a>
                @endforeach
            @endif
        </div>
    </div><!-- End toolbar -->
</div><!-- End content bar -->

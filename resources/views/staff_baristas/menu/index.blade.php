@extends('staff_baristas.layouts.master')
@section('custom-css')
<style>
.container-search {
    display: flex;
    justify-content: center;
    margin-bottom: 15px;
}

#searchInput {
    width: 50%;
}

.menu-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
    padding: 10px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    gap: 20px;
}

.menu-card img {
    width: 85px;
    height: 85px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;

}

.menu-info {
    position: relative;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 5px;
}

.menu-info h5 {
    font-size: 18px;
    font-weight: bold;
    margin: 0;
}

.menu-info p {
    font-size: 14px;
    color: #666;
    margin: 0;
}

.menu-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}


@media (max-width: 1200px) {
    .menu-grid {
        grid-template-columns: repeat(2, 1fr);
        /* 2 cột khi màn hình nhỏ hơn 1200px */
    }
}

@media (max-width: 768px) {
    .menu-grid {
        grid-template-columns: repeat(1, 1fr);
        /* 1 cột khi màn hình nhỏ hơn 768px */
    }
}

.toggle-switch {
    display: inline-block;
    width: 50px;
    height: 24px;
    position: relative;
    flex-shrink: 0;
}

.toggle-switch input {
    display: none;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    border-radius: 24px;
    transition: .3s;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    border-radius: 50%;
    transition: .3s;
}

input:checked+.toggle-slider {
    background-color: #4CAF50;
}

input:checked+.toggle-slider:before {
    transform: translateX(26px);
}

.ingredient-list {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    padding: 10px;
    background: #f9f9f9;
    border-radius: 5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    margin-top: 5px;
    z-index: 10;
}

.custom-tabs .nav-link {
    color: #555;
    font-weight: 500;
    border-radius: 8px 8px 0 0;
    transition: all 0.3s ease-in-out;
    padding: 6px 15px;
}

.custom-tabs .nav-link:hover {
    color: #000;
    background: #f8f9fa;
    border-color: #dee2e6 #dee2e6 transparent;
}

.custom-tabs .nav-link.active {
    color: #fff;
    background: #0049ab;
    border-color: #0049ab #0049ab transparent;
    font-weight: bold;
    box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.1);
}
</style>
@endsection

@section('title')
Thực đơn
@endsection

@section('feature-title')
Thực đơn
@endsection

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }} position-relative">
        {{ Session::get('alert-' . $msg) }}
        <button type="button" class="btn-close position-absolute end-0 me-2" data-bs-dismiss="alert"
            aria-label="Close"></button>
    </p>
    @endif
    @endforeach
</div>

<div class="container-search mt-3">
    <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm món...">
</div>

<ul class="nav nav-tabs custom-tabs m-3" id="itemTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button"
            role="tab">
            Tất cả
        </button>
    </li>
    @foreach($categories as $category)
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="cat-{{ $category->category_id }}-tab" data-bs-toggle="tab"
            data-bs-target="#cat-{{ $category->category_id }}" type="button" role="tab">
            {{ $category->name }}
        </button>
    </li>
    @endforeach
</ul>

<div class="tab-content mx-3 my-4" id="itemTabsContent">
    <!-- Tab Tất cả -->
    <div class="tab-pane fade show active" id="all" role="tabpanel">
        <div class="menu-grid">
            @foreach ($categories as $category)
            @foreach ($category->items as $item)
            <div class="menu-card" data-category="{{ $item->category_id }}">
                <img src="{{ asset('storage/uploads/' . $item->image_url) }}" alt="{{ $item->name }}">
                <div class="menu-info">
                    <h5>{{ $item->name }} - {{ number_format($item->price, 0, ',', '.') }}đ</h5>
                    <p>{{ $item->description }}</p>
                    @if (!$item->is_available && $item->reason)
                    <p class="text-danger"><strong>Lý do:</strong> {{ $item->reason }}</p>
                    @endif
                    <button class="btn btn-link text-black toggle-ingredients" data-id="{{ $item->item_id }}">
                        <i class="fa-solid fa-angle-down"></i>
                    </button>


                    <div class="ingredient-list" id="ingredients-{{ $item->item_id }}">
                        <ul>
                            @foreach($item->ingredients as $ingredient)
                            <li>{{ $ingredient->ingredient->name }} ({{ $ingredient->quantity_per_unit }}
                                {{ $ingredient->ingredient->unit }})</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" class="toggle-available" data-id="{{ $item->item_id }}"
                        {{ $item->is_available ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            @endforeach
            @endforeach
        </div>
    </div>

    <!-- Tab theo từng danh mục -->
    @foreach ($categories as $category)
    <div class="tab-pane fade" id="cat-{{ $category->category_id }}" role="tabpanel">
        <div class="menu-grid">
            @foreach ($category->items as $item)
            <div class="menu-card">
                <img src="{{ asset('storage/uploads/' . $item->image_url) }}" alt="{{ $item->name }}">
                <div class="menu-info">
                    <h5>{{ $item->name }} - {{ number_format($item->price, 0, ',', '.') }}đ</h5>
                    <p>{{ $item->description }}</p>
                    @if (!$item->is_available && $item->reason)
                    <p class="text-danger"><strong>Lý do:</strong> {{ $item->reason }}</p>
                    @endif
                    <button class="btn btn-link text-black toggle-ingredients" data-id="{{ $item->item_id }}">
                        <i class="fa-solid fa-angle-down"></i>
                    </button>

                    <div class="ingredient-list" id="ingredients-{{ $item->item_id }}">
                        <ul>
                            @foreach($item->ingredients as $ingredient)
                            <li>{{ $ingredient->ingredient->name }} ({{ $ingredient->quantity_per_unit }}
                                {{ $ingredient->ingredient->unit }})</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" class="toggle-available" data-id="{{ $item->item_id }}"
                        {{ $item->is_available ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <!-- Modal nhập lý do -->
    <div class="modal fade" id="reasonModal" tabindex="-1" aria-labelledby="reasonModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reasonModalLabel">Nhập lý do tắt sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="reasonInput" class="form-control" rows="3" placeholder="Nhập lý do..."></textarea>
                    <small class="text-danger d-none" id="reasonError">Vui lòng nhập lý do.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="saveReasonButton">Lưu</button>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('custom-scripts')
<script>
$(document).ready(function() {
    let currentItemId = null;
    let isReasonSaved = false;

    // Xử lý bật/tắt trạng thái món
    $('.toggle-available').change(function() {
        let itemId = $(this).data('id');
        let isChecked = $(this).is(':checked');

        if (!isChecked) {
            // Nếu tắt trạng thái, hiển thị modal nhập lý do
            currentItemId = itemId;
            isReasonSaved = false;
            $('#reasonModal').modal('show');
        } else {
            // Nếu bật trạng thái, cập nhật trực tiếp
            updateAvailability(itemId, 1, null);
        }
    });

    // Khi modal bị đóng mà không lưu lý do, bật lại trạng thái
    $('#reasonModal').on('hidden.bs.modal', function() {
        if (currentItemId !== null && !isReasonSaved) {
            // Bật lại trạng thái checkbox
            $(`.toggle-available[data-id="${currentItemId}"]`).prop('checked', true);
            currentItemId = null; // Reset lại giá trị
        }
    });

    // khi nhấn nút "Lưu" trong modal
    $('#saveReasonButton').on('click', function() {
        let reason = $('#reasonInput').val().trim();

        if (reason === '') {
            $('#reasonError').removeClass('d-none');
            return;
        }

        $('#reasonError').addClass('d-none');
        $('#reasonModal').modal('hide');

        isReasonSaved = true;

        // Gửi yêu cầu cập nhật trạng thái và lý do
        updateAvailability(currentItemId, 0, reason);
    });

    // Hàm cập nhật trạng thái sản phẩm
    function updateAvailability(itemId, isAvailable, reason) {
        $.ajax({
            url: `/staff_baristas/menu/toggle-availability/${itemId}`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                is_available: isAvailable,
                reason: reason
            },
            success: function(response) {
                if (response.success) {
                    let message = `
                        <p class="alert alert-success position-relative">
                            ${response.message}
                            <button type="button" class="btn-close position-absolute end-0 me-2" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </p>
                    `;

                    $('.flash-message').empty().append(message);

                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errorMessage = xhr.responseJSON.message;
                    alert(errorMessage); // Hiển thị lỗi nếu không nhập lý do
                }
            }
        });
    }

    // Xử lý bật/tắt trạng thái món
    // $('.toggle-available').change(function() {
    //     let itemId = $(this).data('id');
    //     let isChecked = $(this).is(':checked');

    //     $.ajax({
    //         url: `/staff_baristas/menu/toggle-availability/${itemId}`,
    //         method: 'POST',
    //         data: {
    //             _token: '{{ csrf_token() }}'
    //         },
    //         success: function(response) {
    //             if (response.success) {
    //                 let message = `
    //                     <p class="alert alert-success position-relative">
    //                         Cập nhật thành công
    //                         <button type="button" class="btn-close position-absolute end-0 me-2" data-bs-dismiss="alert"
    //                             aria-label="Close"></button>
    //                     </p>
    //                 `;

    //                 $('.flash-message').empty().append(message);

    //                 setTimeout(function() {
    //                     $('.flash-message .alert').fadeOut('slow');
    //                 }, 3000);
    //             }
    //         }
    //     });
    // });

    // Xử lý hiển thị danh sách nguyên liệu
    $('.toggle-ingredients').click(function() {
        let itemId = $(this).data('id');
        let ingredientList = $('#ingredients-' + itemId);
        let icon = $(this).find('i');

        ingredientList.slideToggle();

        icon.toggleClass('fa-angle-down fa-angle-up');
    });

    // Xử lý tìm kiếm sản phẩm
    $('#searchInput').on('keyup', function() {
        let keyword = $(this).val().toLowerCase();
        $('.menu-card').each(function() {
            let productName = $(this).find('h5').text().toLowerCase();
            if (productName.includes(keyword)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Tự động đóng thông báo sau 5 giây
    setTimeout(function() {
        $('.flash-message .alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection
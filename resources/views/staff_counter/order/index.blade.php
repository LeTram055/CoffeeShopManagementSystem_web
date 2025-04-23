@extends('staff_counter.layouts.master')

@section('title', 'Đặt hàng')
@section('feature-title', 'Đặt hàng')

@section('custom-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
/* Layout chia 2 cột */
.order-container {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.items-column {
    flex: 1 1 55%;
    border-right: 1px solid #ccc;
    padding: 1rem;
    max-height: 80vh;
    overflow-y: auto;
}

.order-summary-column {
    flex: 1 1 40%;
    padding: 1rem;
    max-height: 80vh;
    overflow-y: auto;
}

@media (max-width: 768px) {
    .order-container {
        flex-direction: column;
    }

    .items-column,
    .order-summary-column {
        flex: 1 1 100%;
        border-right: none;
        max-height: none;
    }
}

/* Card sản phẩm */
.item-card {
    cursor: pointer;
    box-shadow: 0px 4px 10px #0049ab;
    transition: transform 0.2s ease-in-out;
}

.item-card:hover {
    transform: scale(1.01);
    box-shadow: 0px 6px 15px #0049ab;
}

/* Ô input note mờ nền */
.note-input {
    background: rgba(255, 255, 255, 0.8);
}

/* Style cho select2 */
.select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding-left: 6px;
    padding-right: 6px;
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

@section('content')
<div class="container-fluid my-3">
    <div class="order-container">
        <!-- Cột sản phẩm -->
        <div class="items-column">
            <!-- Ô tìm kiếm sản phẩm -->
            <div class="mb-3">
                <input type="text" id="itemSearch" class="form-control" placeholder="Tìm kiếm sản phẩm...">
            </div>

            <!-- Các tab danh mục -->
            <ul class="nav nav-tabs custom-tabs mb-3" id="itemTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all"
                        type="button" role="tab">
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

            <!-- Nội dung tab -->
            <div class="tab-content" id="itemTabsContent">
                <!-- Tab Tất cả -->
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    <div class="row" id="allitems">
                        @foreach ($categories as $category)
                        @foreach ($category->items as $item)
                        <div class="col-6 col-md-4 mb-3 item-card-container">
                            <div class="card item-card" data-id="{{ $item->item_id }}" data-name="{{ $item->name }}"
                                data-price="{{ $item->price }}">
                                <img src="{{ asset('storage/uploads/' . $item->image_url) }}" class="card-img-top"
                                    alt="{{ $item->name }}" style="height: 100px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <h6 class="card-title text-center">{{ $item->name }}</h6>
                                    <p class="card-text text-center">{{ number_format($item->price, 0, ',', '.') }} đ
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endforeach
                    </div>
                </div>
                <!-- Các tab theo danh mục -->
                @foreach($categories as $category)
                <div class="tab-pane fade" id="cat-{{ $category->category_id }}" role="tabpanel">
                    <div class="row">
                        @foreach($category->items as $item)
                        <div class="col-6 col-md-4 mb-3 item-card-container">
                            <div class="card item-card" data-id="{{ $item->item_id }}" data-name="{{ $item->name }}"
                                data-price="{{ $item->price }}">
                                <img src="{{ asset('storage/uploads/' . $item->image_url) }}" class="card-img-top"
                                    alt="{{ $item->name }}" style="height: 100px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <h6 class="card-title text-center">{{ $item->name }}</h6>
                                    <p class="card-text text-center">{{ number_format($item->price, 0, ',', '.') }} đ
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Cột đơn hàng -->
        <div class="order-summary-column">
            <!-- Thông báo lỗi -->
            <div id="orderError" class="alert alert-danger mb-1 d-none"></div>

            <h4 class="title2 text-center">Thông tin đơn hàng</h4>

            <!-- Ô chọn khách hàng -->
            <div class="mb-3">
                <label for="customerSelect" class="form-label fw-bold">Khách hàng:</label>
                <select id="customerSelect" class="form-control select2">
                    <option value="">Chọn khách hàng...</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->customer_id }}">{{ $customer->name }} - {{ $customer->phone_number }}
                    </option>
                    @endforeach
                </select>
                <button id="addCustomerBtn" type="button" class="btn btn-outline-primary btn-sm mt-2">Thêm khách hàng
                    mới</button>
            </div>

            <!-- Order summary table -->
            <div class="order-summary-table mb-3">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th class="text-center">Tên sản phẩm</th>
                            <th class="text-center">Giá</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-center">Ghi chú</th>
                            <th class="text-center">Xóa</th>
                        </tr>
                    </thead>
                    <tbody id="orderItemsContainer">
                        <tr>
                            <td colspan="5" class="text-center text-muted">Chưa có sản phẩm nào được chọn.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tổng tiền đơn hàng -->
            <div class="mb-3 text-end">
                <h5>Tổng tiền: <span id="orderTotal">0</span> đ</h5>
            </div>
            <!-- Nút xác nhận đơn hàng -->
            <div class="text-center">
                <button id="submitOrder" class="btn btn-success">Xác nhận đơn hàng</button>
            </div>


        </div>
    </div>
</div>

<!-- Modal thêm khách hàng mới -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalLabel">Thêm khách hàng mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <div id="customerError" class="alert alert-danger d-none"></div>

                <div class="mb-3">
                    <label for="newCustomerName" class="form-label">Tên khách hàng</label>
                    <input type="text" id="newCustomerName" class="form-control" placeholder="Nhập tên khách hàng"
                        required>
                </div>
                <div class="mb-3">
                    <label for="newCustomerPhone" class="form-label">Số điện thoại</label>
                    <input type="text" id="newCustomerPhone" class="form-control" placeholder="Nhập số điện thoại"
                        required>
                </div>
                <div class="mb-3">
                    <label for="newCustomerNotes" class="form-label">Ghi chú</label>
                    <textarea id="newCustomerNotes" class="form-control" placeholder="Nhập ghi chú"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="saveCustomer" class="btn btn-primary">Lưu khách hàng</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
// Khởi tạo select2 cho ô chọn khách hàng
$('#customerSelect').select2({
    placeholder: 'Chọn khách hàng...',
    allowClear: true,

});

// Ẩn/hiện nút Thêm khách hàng dựa vào lựa chọn
$('#customerSelect').on('change', function() {
    if ($(this).val()) {
        $('#addCustomerBtn').hide();
    } else {
        $('#addCustomerBtn').show();
    }
});

// Khi bấm Thêm khách hàng mới, mở modal
$('#addCustomerBtn').on('click', function() {
    $('#customerModal').modal('show');
});

// Lưu khách hàng mới qua AJAX
$('#saveCustomer').on('click', function() {
    let name = $('#newCustomerName').val();
    let phone = $('#newCustomerPhone').val();
    let notes = $('#newCustomerNotes').val();
    if (!name) {
        alert('Tên khách hàng không được để trống');
        return;
    }
    $.ajax({
        url: '/staff_counter/order/save-customer',
        type: 'POST',
        data: {
            name: name,
            phone_number: phone,
            notes: notes,
            _token: '{{ csrf_token() }}'
        },
        success: function(data) {
            // Thêm option mới vào select2 và set là đã chọn
            let newOption = new Option(data.name + ' - ' + data.phone_number, data.customer_id,
                true, true);
            $('#customerSelect').append(newOption).trigger('change');
            $('#customerModal').modal('hide');
        },
        error: function(xhr) {
            let errors = xhr.responseJSON.errors;
            let errorHtml = '';
            $.each(errors, function(key, messages) {
                errorHtml += messages.join('<br>') + '<br>';
            });
            $('#customerError').removeClass('d-none').html(errorHtml);
        }
    });
});

// Biến toàn cục cho đơn hàng
let orderItems = [];

$(document).on('click', '.item-card', function() {
    const id = $(this).data('id');
    const name = $(this).data('name');
    const price = parseFloat($(this).data('price'));

    // Kiểm tra nếu sản phẩm đã tồn tại trong đơn hàng
    let existing = orderItems.find(item => item.id === id);
    if (existing) {
        existing.quantity += 1;
    } else {
        orderItems.push({
            id,
            name,
            price,
            quantity: 1,
            note: ''
        });
    }
    renderOrderItems();
});

// Tìm kiếm sản phẩm (lọc card theo tên)
$('#itemSearch').on('input', function() {
    let query = $(this).val().toLowerCase();
    $('.item-card-container').each(function() {
        let itemName = $(this).find('.card-title').text().toLowerCase();
        $(this).toggle(itemName.indexOf(query) > -1);
    });
});

// Hàm render đơn hàng (hiển thị dưới dạng bảng)
function renderOrderItems() {
    const container = $('#orderItemsContainer');
    container.empty();
    if (orderItems.length === 0) {
        container.html('<tr><td colspan="5" class="text-center text-muted">Chưa có sản phẩm nào được chọn.</td></tr>');
        updateTotal();
        return;
    }
    orderItems.forEach((item, index) => {
        let rowHtml = `
                <tr class="order-item" data-index="${index}">
                    <td>${item.name}</td>
                    <td class="text-end">${number_format(item.price)} đ</td>
                    <td class="text-center">
                        <div class="input-group input-group-sm justify-content-center"  style="min-width: 100px">
                            <button class="btn btn-sm btn-outline-secondary decrease">-</button>
                            <input type="number" class="form-control quantity-input" value="${item.quantity}" style="width: 50px; text-align: center; display: inline-block;">
                            <button class="btn btn-sm btn-outline-secondary increase">+</button>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm note-input" placeholder="Ghi chú" value="${item.note}">
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-danger remove-item"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            `;
        container.append(rowHtml);
    });
    updateTotal();
}

// Hàm cập nhật tổng tiền
function updateTotal() {
    let total = 0;
    orderItems.forEach(item => {
        total += item.price * item.quantity;
    });
    $('#orderTotal').text(number_format(total));
}

// Format số theo định dạng Việt Nam
function number_format(number) {
    return new Intl.NumberFormat('vi-VN').format(number);
}

//nhập trực tiếp input số lượng
$('#orderItemsContainer').on('input', '.quantity-input', function() {
    const index = $(this).closest('.order-item').data('index');
    let newQuantity = parseInt($(this).val(), 10);

    // Kiểm tra giá trị nhập vào có hợp lệ không (chỉ là số nguyên dương)
    if (isNaN(newQuantity) || newQuantity <= 0) {
        $(this).val(orderItems[index].quantity); // Reset lại giá trị nếu không hợp lệ
        return;
    }

    // Cập nhật số lượng vào mảng orderItems
    orderItems[index].quantity = newQuantity;
    renderOrderItems(); // Cập nhật lại giao diện
});


// Xử lý tăng, giảm số lượng và cập nhật ghi chú
$('#orderItemsContainer').on('click', '.increase', function() {
    const index = $(this).closest('.order-item').data('index');
    orderItems[index].quantity += 1;
    renderOrderItems();
});
$('#orderItemsContainer').on('click', '.decrease', function() {
    const index = $(this).closest('.order-item').data('index');
    if (orderItems[index].quantity > 1) {
        orderItems[index].quantity -= 1;
        renderOrderItems();
    }
});
$('#orderItemsContainer').on('input', '.note-input', function() {
    const index = $(this).closest('.order-item').data('index');
    orderItems[index].note = $(this).val();
});
$('#orderItemsContainer').on('click', '.remove-item', function() {
    const index = $(this).closest('.order-item').data('index');
    orderItems.splice(index, 1);
    renderOrderItems();
});

// Xử lý submit đơn hàng
$('#submitOrder').on('click', function() {
    const orderType = $('input.order-type:checked').val();
    let orderData = {

        items: orderItems,

        customer_id: $('#customerSelect').val()
    };
    $.ajax({
        url: '/staff_counter/order/save',
        type: 'POST',
        data: {
            ...orderData,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {

            if (response.success) {
                // Hiển thị flash message thành công (alert-success)
                $('#orderError')
                    .removeClass('d-none alert-danger')
                    .addClass('alert-success')
                    .html(response.message);

                // Sau 3 giây tự ẩn thông báo
                setTimeout(function() {
                    $('#orderError').addClass('d-none').html('');
                }, 3000);

                // Reset lại đơn hàng
                orderItems = [];
                renderOrderItems();
                // Reset lại ô chọn khách hàng
                $('#customerSelect').val('').trigger('change');
                // Reset nút chọn bàn về trạng thái mặc định và ẩn nó
                $('#chooseTableBtn').text('Chọn bàn').addClass('d-none');
            } else {
                $('#orderError')
                    .removeClass('d-none alert-success')
                    .addClass('alert-danger')
                    .html(response.message);
            }
        },
        error: function(xhr) {
            let errors = xhr.responseJSON.errors;

            let errorHtml = '';
            if (Array.isArray(errors)) {
                // Trường hợp lỗi là mảng thông báo đơn thuần
                errorHtml = errors.join('<br>');
            } else if (typeof errors === 'object') {
                // Trường hợp lỗi validation theo field
                $.each(errors, function(key, messages) {
                    errorHtml += messages.join('<br>') + '<br>';
                });
            } else {
                // Một lỗi không xác định
                errorHtml = 'Đã xảy ra lỗi không xác định.';
            }
            $('#orderError')
                .removeClass('d-none alert-success')
                .addClass('alert-danger')
                .html(errorHtml);

            // Sau 3 giây tự ẩn thông báo
            setTimeout(function() {
                $('#orderError').addClass('d-none').html('');
            }, 3000);
        }
    });
});
</script>
@endsection
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Đơn hàng #{{ $order->order_id }}</strong>
        @if ($order->status !== 'completed')
        <button class="btn btn-success" id="complete-order" data-id="{{ $order->order_id }}">Hoàn thành</button>
        @endif
    </div>
    <div class="card-body">
        <h5>Món đã đặt:</h5>
        <div class="row">
            @foreach($order->orderItems as $item)
            @if($item->quantity - $item->completed_quantity > 0)
            <div class="col-lg-6 col-12 mb-3">
                <div class="card p-2 d-flex flex-row align-items-center">
                    <img src="{{ asset('storage/uploads/' . $item->item->image_url) }}" alt="{{ $item->item->name }}"
                        class="rounded me-2" style="width: 60px; height: 60px; object-fit: cover;">
                    <div class="flex-grow-1">
                        <strong>{{ $item->item->name }}</strong>
                        <p class="text-muted mb-1"><em>Ghi chú: {{ $item->note ?? 'Không có' }}</em></p>

                    </div>

                    <strong class="text-end">x {{ $item->quantity - $item->completed_quantity }}</strong>
                    @if ($order->order_type === 'dine_in' && ($item->status === 'order' || ($item->status ===
                    'completed' && ($item->quantity - $item->completed_quantity) > 0)) )
                    <!-- Chỉ hiển thị nút nếu là đơn tại chỗ và chưa có lỗi -->
                    <button class="btn btn-danger btn-sm ms-2 report-issue" data-itemid="{{ $item->item_id }}"
                        data-orderid="{{ $item->order_id }}">Gặp trục trặc</button>
                    @endif

                    @if($item->status === 'issue')
                    <span class="badge bg-warning ms-2">Có lỗi</span>
                    @endif
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>

<!-- Modal Nhập lý do gặp trục trặc -->
<div class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="issueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="issueModalLabel">Nhập lý do gặp trục trặc cho món</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <textarea id="issueReason" class="form-control" rows="3" placeholder="Nhập lý do..."></textarea>
                <div id="issueReasonError" class="text-danger mt-2" style="display: none;">Vui lòng nhập lý do!</div>
                <input type="hidden" id="itemId" />
                <input type="hidden" id="orderId" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submitIssue">Gửi lý do</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.report-issue').click(function() {
        let itemId = $(this).data('itemid');
        let orderId = $(this).data('orderid'); // Lấy orderId từ thuộc tính data

        $('#itemId').val(itemId);
        $('#orderId').val(orderId);
        $('#issueModal').modal('show');

    });

    $('#submitIssue').click(function() {
        let orderId = $('#orderId').val(); // Lấy orderId từ input ẩn
        let itemId = $('#itemId').val();
        let reason = $('#issueReason').val();
        let errorDiv = $('#issueReasonError');

        // Kiểm tra giá trị
        if (reason.trim() === '') {
            errorDiv.text('Vui lòng nhập lý do!').show(); // Hiển thị lỗi
            return;
        } else {
            errorDiv.hide(); // Ẩn lỗi nếu không có lỗi
        }

        $.ajax({
            url: "{{ url('staff_baristas/order/report-issue') }}/" + orderId,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                item_id: itemId,
                reason: reason
            },
            success: function(response) {
                // Hiển thị thông báo thành công trong flash-message
                let messageHtml = '<p class="alert alert-success position-relative">' +
                    response.message +
                    '<button type="button" class="btn-close position-absolute end-0 me-2" data-bs-dismiss="alert" aria-label="Close"></button></p>';

                $('.flash-message').html(messageHtml).show();
                $('#issueModal').modal('hide');

                // Tự động ẩn thông báo sau 5 giây
                setTimeout(function() {
                    $('.flash-message').fadeOut('slow');
                }, 5000);



            },
            error: function(xhr) {
                // Hiển thị thông báo lỗi trong flash-message
                let errorHtml = '<p class="alert alert-danger position-relative">' +
                    (xhr.responseJSON.message || 'Có lỗi xảy ra, vui lòng thử lại!') +
                    '<button type="button" class="btn-close position-absolute end-0 me-2" data-bs-dismiss="alert" aria-label="Close"></button></p>';

                $('.flash-message').html(errorHtml).show();

                // Tự động ẩn thông báo sau 5 giây
                setTimeout(function() {
                    $('.flash-message').fadeOut('slow');
                }, 5000);
            }
        });
    });
});

$(document).ready(function() {
    $('#complete-order').click(function() {
        let orderId = $(this).data('id');

        $.ajax({
            url: "{{ url('staff_baristas/order/complete') }}/" + orderId,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                method: "POST"
            },
            success: function(response) {
                let messageHtml = '<p class="alert alert-success position-relative">' +
                    response.message +
                    '<button type="button" class="btn-close position-absolute end-0 me-2" data-bs-dismiss="alert" aria-label="Close"></button></p>';

                $('.flash-message').html(messageHtml).show();

                setTimeout(function() {
                    $('.flash-message').fadeOut('slow', function() {
                        location.reload();
                    });
                }, 5000);
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ?
                    xhr.responseJSON.message :
                    'Có lỗi xảy ra, vui lòng thử lại!';

                // Hiển thị thông báo lỗi trong flash-message
                let errorHtml = '<p class="alert alert-danger position-relative">' +
                    errorMessage +
                    '<button type="button" class="btn-close position-absolute end-0 me-2" data-bs-dismiss="alert" aria-label="Close"></button></p>';

                $('.flash-message').html(errorHtml).show();

                // Tự động ẩn thông báo sau 5 giây
                setTimeout(function() {
                    $('.flash-message').fadeOut('slow');
                }, 5000);
            }
        });
    });
});
</script>
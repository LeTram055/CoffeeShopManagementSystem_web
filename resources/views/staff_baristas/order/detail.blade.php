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
            <div class="col-lg-6 col-12 mb-3">
                <div class="card p-2 d-flex flex-row align-items-center">
                    <img src="{{ asset('storage/uploads/' . $item->item->image_url) }}" alt="{{ $item->item->name }}"
                        class="rounded me-2" style="width: 60px; height: 60px; object-fit: cover;">
                    <div class="flex-grow-1">
                        <strong>{{ $item->item->name }}</strong>
                        <p class="text-muted mb-1"><em>Ghi chú: {{ $item->note ?? 'Không có' }}</em></p>
                    </div>
                    <strong class="text-end">x {{ $item->quantity }}</strong>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
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
                let errorHtml =
                    '<p class="alert alert-danger position-relative">Có lỗi xảy ra, vui lòng thử lại!' +
                    '<button type="button" class="btn-close position-absolute end-0 me-2" data-bs-dismiss="alert" aria-label="Close"></button></p>';

                $('.flash-message').html(errorHtml).show();
            }
        });
    });
});
</script>
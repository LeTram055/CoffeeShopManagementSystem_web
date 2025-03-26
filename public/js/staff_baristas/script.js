$(document).ready(function() {
    const socket = io("http://localhost:3000");

    socket.on("connect", () => {
        console.log("Connected to WebSocket server");
    });

    socket.on("order.event", (order) => {
        if(order.data.event_type == 'created') {
            showToast(`Đơn hàng #${order.data.order.order_id} vừa được tạo!`, "bg-success");
        } else if(order.data.event_type == 'updated') {
            showToast(`Đơn hàng #${order.data.order.order_id} đã được cập nhật!`, "bg-warning");
        } else if(order.data.event_type == 'cancelled') {
            showToast(`Đơn hàng #${order.data.order.order_id} đã bị hủy!`, "bg-danger");
        } 
    
    });

    socket.on("lowstock.event", (ingredient) => {
        
        showToast(
            `Nguyên liệu "${ingredient.data.name}" chỉ còn ${ingredient.data.quantity}, dưới mức tối thiểu (${ingredient.data.min_quantity})!`,
            "bg-danger"
        );
    });

    

    // Hàm hiển thị Toast
    function showToast(message, bgClass) {
        let toastHtml = `
            <div class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`;

        $('.toast-container').append(toastHtml);
        let newToast = $('.toast-container .toast').last();
        let toast = new bootstrap.Toast(newToast[0], { autohide: false });
        toast.show();
    }


});
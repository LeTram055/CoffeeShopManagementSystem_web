$(document).ready(function() {
    const socket = io("http://localhost:3000");

    socket.on("connect", () => {
        console.log("Connected to WebSocket server");
    });

    socket.on("order.event", (order) => {
        if(order.data.event_type == 'completed') {
            showToast(`Đơn hàng #${order.data.order.order_id} đã được pha chế xong!`, "bg-success");
        } else if(order.data.event_type == 'payment') {
            showToast(`Đơn hàng #${order.data.order.order_id} vừa được thanh toán!`, "bg-primary");
        } 
    
    });

    socket.on("order.issue", (data) => {
        
        if(data.data.order_type == 'takeaway') {
            showToast(`Đơn hàng #${data.data.order_id}, Món: ${data.data.item_name} gặp trục trặc: ${data.data.reason}`, "bg-danger");
        }
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
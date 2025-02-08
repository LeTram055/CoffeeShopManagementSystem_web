$(document).ready(function() {
    // Toggle sidebar khi màn hình nhỏ
    $('#sidebarToggle').on('click', function() {
        $('#sidebarMenu').toggleClass('active');
        $('#overlay').toggleClass('active');
    });

    // Đóng sidebar khi click ngoài (trên overlay)
    $('#overlay').on('click', function() {
        $('#sidebarMenu').removeClass('active');
        $('#overlay').removeClass('active');
    });
});
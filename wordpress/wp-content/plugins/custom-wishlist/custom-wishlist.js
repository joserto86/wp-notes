jQuery(document).ready(function ($) {
    $('#custom_add_wishlist').click(function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'wp-admin/admin-ajax.php',
            data: WishList,
        }).success(function (response) {
            $('#custom_add_wishlist_div').html('You wanted this');
        }).error(function (err){
            console.log(err);
        });
    });
});